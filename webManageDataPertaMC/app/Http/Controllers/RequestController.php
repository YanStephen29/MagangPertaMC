<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Document;
use App\Models\Project;
use App\Models\Boq;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($documentNo)
    {
        try {
            $document = Document::with(['request', 'tahapans'])->where('no_request', $documentNo)->firstOrFail();
            
            // Check if document tahapan is completed
            if ($document->getProgressPercentage() < 100) {
                return response()->json([
                    'error' => 'Document tahapan belum selesai',
                    'message' => 'Tahapan document harus mencapai 100% (EPC TO PROCUREMENT) sebelum dapat mengakses request.'
                ], 422);
            }

            // Check if project has BOQ
            $firstTool = $document->tools()->first();
            if ($firstTool && $firstTool->project) {
                $project = $firstTool->project;
                
                // Check if BOQ exists
                if (!$project->boq) {
                    return response()->json([
                        'error' => 'BOQ belum dibuat',
                        'message' => 'Project ini belum memiliki BOQ. Silakan buat BOQ terlebih dahulu sebelum membuat request.',
                        'action' => 'create_boq',
                        'project_id' => $project->no_IO
                    ], 422);
                }
                
                // Check if BOQ has sections
                if ($project->boq->sections()->count() === 0) {
                    return response()->json([
                        'error' => 'BOQ masih kosong',
                        'message' => 'BOQ sudah ada tetapi belum memiliki section. Silakan tambah section dan detail pada BOQ terlebih dahulu.',
                        'action' => 'manage_boq',
                        'project_id' => $project->no_IO
                    ], 422);
                }

                // Check if BOQ sections have details
                $hasDetails = false;
                foreach ($project->boq->sections as $section) {
                    if ($section->details()->count() > 0) {
                        $hasDetails = true;
                        break;
                    }
                }
                
                if (!$hasDetails) {
                    return response()->json([
                        'error' => 'BOQ belum lengkap',
                        'message' => 'BOQ dan section sudah ada tetapi belum memiliki detail. Silakan tambah detail pada section BOQ terlebih dahulu.',
                        'action' => 'manage_boq',
                        'project_id' => $project->no_IO
                    ], 422);
                }
            }

            // If request doesn't exist through any tool, create one for the first tool
            $firstTool = $document->tools()->first();
            if (!$document->request && $firstTool) {
                $request = Request::create([
                    'type_surat' => 'SPS',
                    'jenis_req' => 'PO',
                    'no_surat' => '',
                    'date_req' => now(),
                    'status_req' => 'On Proses',
                    'tool_id' => $firstTool->idTools
                ]);
                $document->load('request'); // Reload relationship
            }

            return response()->json([
                'document' => $document,
                'request' => $document->request
            ]);
        } catch (\Exception $e) {
            Log::error('Request show error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error loading request data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HttpRequest $httpRequest, $requestId)
    {
        try {
            $request = Request::findOrFail($requestId);
            
            // Check if can be accessed through tool's document
            $document = $request->tool?->document;
            if (!$document || $document->getProgressPercentage() < 100) {
                return response()->json([
                    'error' => 'Document tahapan belum selesai',
                    'message' => 'Tahapan document harus mencapai 100% (EPC TO PROCUREMENT) sebelum dapat mengakses request.'
                ], 422);
            }

            $validated = $httpRequest->validate([
                'type_surat' => 'required|in:' . implode(',', Request::TYPE_SURAT_OPTIONS),
                'jenis_req' => 'required|in:' . implode(',', Request::JENIS_REQ_OPTIONS),
                'no_surat' => 'required|string|max:45',
                'date_req' => 'required|date',
                'status_req' => 'required|in:' . implode(',', Request::STATUS_REQ_OPTIONS),
            ]);

            $request->update($validated);

            return response()->json([
                'success' => 'Request berhasil diperbarui!',
                'request' => $request
            ]);
        } catch (\Exception $e) {
            Log::error('Request update error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error updating request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if document can access request based on tahapan
     */
    public function checkAccess($documentNo)
    {
        try {
            $document = Document::with(['tahapans'])->where('no_request', $documentNo)->firstOrFail();
            
            $canAccess = $document->getProgressPercentage() >= 100;
            
            return response()->json([
                'can_access' => $canAccess,
                'progress' => $document->getProgressPercentage(),
                'current_tahapan' => $document->getCurrentTahapan()?->namaTahapan
            ]);
        } catch (\Exception $e) {
            Log::error('Request access check error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error checking access',
                'can_access' => false
            ], 500);
        }
    }
}
