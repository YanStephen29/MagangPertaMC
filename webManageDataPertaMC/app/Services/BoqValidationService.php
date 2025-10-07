<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Detail;
use App\Models\Tool;

class BoqValidationService
{
    /**
     * Validasi request tool berdasarkan BOQ details
     */
    public function validateToolRequest(Project $project, array $toolData)
    {
        $validationResult = [
            'is_valid' => false,
            'errors' => [],
            'warnings' => [],
            'matched_details' => [],
            'suggestions' => [],
            'debug_info' => []
        ];

        // Ambil semua BOQ details untuk project ini
        try {
            $boqDetails = $this->getProjectBoqDetails($project);
            $validationResult['debug_info']['total_boq_details'] = $boqDetails->count();
            $validationResult['debug_info']['project_no_io'] = $project->no_IO;
        } catch (\Exception $e) {
            $validationResult['errors'][] = "Error mengambil data BOQ: " . $e->getMessage();
            $validationResult['debug_info']['query_error'] = $e->getMessage();
            $validationResult['debug_info']['project_no_io'] = $project->no_IO;
            return $validationResult;
        }
        
        if ($boqDetails->isEmpty()) {
            $validationResult['warnings'][] = "Project ini belum memiliki BOQ. Request tool dapat dilanjutkan tanpa validasi BOQ.";
            $validationResult['is_valid'] = true;
            return $validationResult;
        }

        // Cari matching details berdasarkan description dan unit
        $matchingDetails = $this->findMatchingDetails($boqDetails, $toolData);
        
        // Jika tidak ada exact match, coba matching yang lebih fleksibel
        if ($matchingDetails->isEmpty()) {
            $flexiMatchingDetails = $this->findFlexibleMatches($boqDetails, $toolData);
            if (!$flexiMatchingDetails->isEmpty()) {
                $validationResult['warnings'][] = "Tidak ditemukan exact match, tetapi ada item serupa. Silakan periksa saran di bawah.";
                $matchingDetails = $flexiMatchingDetails;
            }
        }
        
        if ($matchingDetails->isEmpty()) {
            $validationResult['errors'][] = "Tidak ditemukan item BOQ yang sesuai dengan deskripsi '{$toolData['Description']}' dan unit '{$toolData['unit']}'.";
            
            // Berikan saran item BOQ yang mirip
            $suggestions = $this->getSimilarItems($boqDetails, $toolData);
            if (!empty($suggestions)) {
                $validationResult['suggestions'] = $suggestions;
            }
            
            // Tambahkan info untuk debugging
            $validationResult['debug_info']['search_criteria'] = [
                'description' => $toolData['Description'],
                'unit' => $toolData['unit']
            ];
            
            return $validationResult;
        }

        // Validasi quantity dan harga untuk setiap matching detail
        $hasValidMatch = false;
        foreach ($matchingDetails as $detail) {
            $validation = $this->validateQuantityAndPrice($detail, $toolData, $project);
            
            if ($validation['is_valid']) {
                $hasValidMatch = true;
                $validationResult['matched_details'][] = $validation;
            } else {
                $validationResult['errors'] = array_merge($validationResult['errors'], $validation['errors']);
                $validationResult['matched_details'][] = $validation;
            }
        }

        $validationResult['is_valid'] = $hasValidMatch;
        
        return $validationResult;
    }

    /**
     * Ambil semua BOQ details untuk project
     */
    private function getProjectBoqDetails(Project $project)
    {
        // Debug: Cek apakah ada BOQ untuk project ini
        \Log::info('Mencari BOQ untuk project', [
            'project_no_io' => $project->no_IO,
            'project_title' => $project->title_project
        ]);

        // Cek BOQ yang ada untuk project ini
        $boqs = \App\Models\Boq::where('project_no_io', $project->no_IO)->get();
        \Log::info('BOQ ditemukan', [
            'count' => $boqs->count(),
            'boq_numbers' => $boqs->pluck('nomorBoq')->toArray()
        ]);

        // Jika tidak ada BOQ, return empty collection
        if ($boqs->isEmpty()) {
            return collect([]);
        }

        // Ambil details dari sections yang terkait dengan BOQ project ini
        // Filter hanya item dengan harga satuan > 0 dan quantity > 0
        return Detail::whereHas('section.boq', function ($query) use ($project) {
            $query->where('project_no_io', $project->no_IO);
        })
        ->where('harga_satuan', '>', 0)
        ->where('quantity', '>', 0)
        ->whereNotNull('harga_satuan')
        ->whereNotNull('quantity')
        ->with(['section.boq', 'parent', 'children'])
        ->get();
    }

    /**
     * Cari details yang matching berdasarkan deskripsi dan unit
     */
    private function findMatchingDetails($details, array $toolData)
    {
        $description = strtolower(trim($toolData['Description']));
        $unit = strtolower(trim($toolData['unit']));

        // Debug info untuk troubleshooting
        $debugInfo = [
            'search_criteria' => [
                'description' => $description,
                'unit' => $unit
            ],
            'total_details_found' => $details->count(),
            'sample_details' => $details->take(10)->map(function($detail) {
                return [
                    'nama_detail' => $detail->nama_detail,
                    'unit' => $detail->unit ?? 'NO_UNIT',
                    'quantity' => $detail->quantity,
                    'section' => $detail->section->nama ?? 'No Section',
                    'boq' => $detail->section->boq->nomorBoq ?? 'No BOQ'
                ];
            })->toArray()
        ];
        
        \Log::info('BOQ Validation Search', $debugInfo);

        $matchingDetails = $details->filter(function ($detail) use ($description, $unit) {
            $detailName = strtolower(trim($detail->nama_detail));
            $detailUnit = strtolower(trim($detail->unit ?? ''));

            // Exact match untuk unit
            $unitMatch = $detailUnit === $unit;
            
            // Fuzzy match untuk description - lebih toleran
            $similarity = $this->calculateSimilarity($detailName, $description);
            $descriptionMatch = 
                str_contains($detailName, $description) ||
                str_contains($description, $detailName) ||
                $similarity > 0.5; // Turunkan threshold dari 0.7 ke 0.5

            // Debug individual matches
            \Log::info('Detail Match Check', [
                'detail_name' => $detailName,
                'detail_unit' => $detailUnit,
                'search_description' => $description,
                'search_unit' => $unit,
                'unit_match' => $unitMatch,
                'description_match' => $descriptionMatch,
                'similarity_score' => $similarity,
                'overall_match' => $unitMatch && $descriptionMatch
            ]);

            return $unitMatch && $descriptionMatch;
        });

        \Log::info('Matching Results', [
            'found_matches' => $matchingDetails->count(),
            'matches' => $matchingDetails->map(function($detail) {
                return [
                    'nama_detail' => $detail->nama_detail,
                    'unit' => $detail->unit,
                    'quantity' => $detail->quantity
                ];
            })->toArray()
        ]);

        return $matchingDetails;
    }

    /**
     * Cari matches dengan kriteria yang lebih fleksibel
     */
    private function findFlexibleMatches($details, array $toolData)
    {
        $description = strtolower(trim($toolData['Description']));
        $unit = strtolower(trim($toolData['unit']));

        return $details->filter(function ($detail) use ($description, $unit) {
            $detailName = strtolower(trim($detail->nama_detail));
            $detailUnit = strtolower(trim($detail->unit ?? ''));

            // Unit matching yang lebih fleksibel
            $unitMatch = $this->isUnitSimilar($detailUnit, $unit);
            
            // Description matching yang lebih fleksibel
            $words = explode(' ', $description);
            $detailWords = explode(' ', $detailName);
            
            $matchingWords = 0;
            foreach ($words as $word) {
                foreach ($detailWords as $detailWord) {
                    if (strlen($word) > 2 && strlen($detailWord) > 2) {
                        if (str_contains($detailWord, $word) || str_contains($word, $detailWord)) {
                            $matchingWords++;
                            break;
                        }
                    }
                }
            }
            
            $descriptionMatch = $matchingWords > 0 && ($matchingWords / count($words)) > 0.3;

            return $unitMatch && $descriptionMatch;
        });
    }

    /**
     * Check if units are similar (considering common variations)
     */
    private function isUnitSimilar($unit1, $unit2)
    {
        // Exact match
        if ($unit1 === $unit2) {
            return true;
        }

        // Common unit variations
        $unitMappings = [
            'pcs' => ['pcs', 'pc', 'piece', 'pieces', 'unit', 'buah'],
            'meter' => ['meter', 'm', 'mtr', 'metre'],
            'liter' => ['liter', 'l', 'litre'],
            'kilogram' => ['kg', 'kilogram', 'kilo'],
            'gram' => ['g', 'gram', 'gr'],
            'ton' => ['ton', 't', 'tonne'],
            'set' => ['set', 'sets'],
            'roll' => ['roll', 'rol'],
            'sheet' => ['sheet', 'lembar', 'lbr'],
            'box' => ['box', 'kotak'],
            'pack' => ['pack', 'pak', 'package']
        ];

        foreach ($unitMappings as $standardUnit => $variations) {
            if (in_array($unit1, $variations) && in_array($unit2, $variations)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validasi quantity dan harga untuk detail tertentu
     */
    private function validateQuantityAndPrice($detail, array $toolData, Project $project)
    {
        $result = [
            'detail' => $detail,
            'is_valid' => true,
            'errors' => [],
            'warnings' => [],
            'availability' => []
        ];

        $requestedQty = (float) $toolData['quantity'];
        $availableQty = (float) $detail->quantity;
        
        // Hitung quantity yang sudah digunakan di tools lain untuk project ini
        $usedQty = $this->getUsedQuantityForDetail($detail, $project);
        $remainingQty = $availableQty - $usedQty;

        // Validasi quantity
        if ($requestedQty > $remainingQty) {
            $result['is_valid'] = false;
            $result['errors'][] = "Quantity yang diminta ({$requestedQty} {$detail->unit}) melebihi sisa yang tersedia ({$remainingQty} {$detail->unit}) dari total BOQ ({$availableQty} {$detail->unit}).";
        }

        // Jika ada harga satuan di tool request, validasi dengan BOQ
        if (isset($toolData['harga_satuan']) && $toolData['harga_satuan'] > 0) {
            $requestedPrice = (float) $toolData['harga_satuan'];
            $boqPrice = (float) $detail->harga_satuan;
            
            if ($requestedPrice > $boqPrice) {
                $result['warnings'][] = "Harga satuan yang diminta (Rp " . number_format($requestedPrice, 0, ',', '.') . ") lebih tinggi dari harga BOQ (Rp " . number_format($boqPrice, 0, ',', '.') . ").";
            }
        }

        // Info ketersediaan dengan informasi section
        $result['availability'] = [
            'item_name' => $detail->nama_detail,
            'section_name' => $detail->section->nama ?? 'N/A',
            'boq_number' => $detail->section->boq->nomorBoq ?? 'N/A',
            'boq_quantity' => $availableQty,
            'used_quantity' => $usedQty,
            'remaining_quantity' => $remainingQty,
            'requested_quantity' => $requestedQty,
            'unit' => $detail->unit,
            'boq_unit_price' => $detail->harga_satuan,
            'boq_total_price' => $detail->getTotalHargaWithChildren()
        ];

        return $result;
    }

    /**
     * Hitung quantity yang sudah digunakan untuk detail tertentu
     */
    private function getUsedQuantityForDetail($detail, Project $project)
    {
        // Cari tools yang sudah dibuat dengan deskripsi dan unit yang sama untuk project ini
        return Tool::where('no_IO', $project->no_IO)
            ->where(function ($query) use ($detail) {
                $query->whereRaw('LOWER(Description) LIKE ?', ['%' . strtolower($detail->nama_detail) . '%'])
                      ->where('unit', $detail->unit);
            })
            ->sum('quantity');
    }

    /**
     * Cari item BOQ yang mirip sebagai saran
     */
    private function getSimilarItems($details, array $toolData)
    {
        $description = strtolower(trim($toolData['Description']));
        $suggestions = [];

        foreach ($details as $detail) {
            $detailName = strtolower(trim($detail->nama_detail));
            $similarity = $this->calculateSimilarity($detailName, $description);
            
            if ($similarity > 0.4) { // Ambil yang similarity > 40%
                $suggestions[] = [
                    'detail' => [
                        'nama_detail' => $detail->nama_detail,
                        'unit' => $detail->unit,
                        'quantity' => $detail->quantity,
                        'harga_satuan' => $detail->harga_satuan,
                        'section_name' => $detail->section->nama ?? 'N/A',
                        'boq_number' => $detail->section->boq->nomorBoq ?? 'N/A'
                    ],
                    'similarity' => $similarity,
                    'reason' => 'Nama mirip dengan deskripsi yang diminta'
                ];
            }
        }

        // Sort berdasarkan similarity dan ambil top 5
        usort($suggestions, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        return array_slice($suggestions, 0, 5);
    }

    /**
     * Hitung similarity antara dua string
     */
    private function calculateSimilarity($str1, $str2)
    {
        // Menggunakan similar_text untuk menghitung similarity
        similar_text($str1, $str2, $percent);
        return $percent / 100;
    }

    /**
     * Generate pesan error yang user-friendly
     */
    public function formatValidationMessage($validationResult)
    {
        $messages = [];

        if (!empty($validationResult['errors'])) {
            $messages['errors'] = $validationResult['errors'];
        }

        if (!empty($validationResult['warnings'])) {
            $messages['warnings'] = $validationResult['warnings'];
        }

        if (!empty($validationResult['suggestions'])) {
            $messages['suggestions'] = "Item BOQ yang mungkin sesuai:";
            foreach ($validationResult['suggestions'] as $suggestion) {
                $detail = $suggestion['detail'];
                $messages['suggestions'] .= "\n- {$detail->nama_detail} ({$detail->quantity} {$detail->unit}) - Rp " . number_format($detail->harga_satuan, 0, ',', '.');
            }
        }

        if (!empty($validationResult['matched_details'])) {
            foreach ($validationResult['matched_details'] as $match) {
                if (isset($match['availability'])) {
                    $avail = $match['availability'];
                    $messages['info'][] = "BOQ tersedia: {$avail['remaining_quantity']} {$match['detail']->unit} (dari total {$avail['boq_quantity']} {$match['detail']->unit})";
                }
            }
        }

        return $messages;
    }

    /**
     * Cek apakah masih ada budget yang tersedia untuk item tertentu
     */
    public function checkBudgetAvailability($detail, $requestedQty)
    {
        $totalBudget = $detail->getTotalHargaWithChildren();
        $requestedBudget = $requestedQty * $detail->harga_satuan;
        
        return [
            'total_budget' => $totalBudget,
            'requested_budget' => $requestedBudget,
            'is_sufficient' => $requestedBudget <= $totalBudget,
            'remaining_budget' => $totalBudget - $requestedBudget
        ];
    }


}