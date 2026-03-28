<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahapan extends Model
{
    use HasFactory;

    protected $primaryKey = 'idTahapan';

    protected $fillable = [
        'no_request',
        'namaTahapan',
        'Date_Tahapan'
    ];

    protected $casts = [
        'Date_Tahapan' => 'date',
    ];

    const TAHAPAN_BELUM_DIPROSES = 'BELUM DI PROSES';
    const TAHAPAN_PROJECT_TO_EPC = 'PROJECT TO EPC (EPC PROCESS)';
    const TAHAPAN_PMO_TO_EPC = 'PMO TO EPC';
    const TAHAPAN_EPC_TO_PROCUREMENT = 'EPC TO PROCUREMENT';

    public static function getTahapanOptions()
    {
        return [
            self::TAHAPAN_BELUM_DIPROSES,
            self::TAHAPAN_PROJECT_TO_EPC,
            self::TAHAPAN_PMO_TO_EPC,
            self::TAHAPAN_EPC_TO_PROCUREMENT,
        ];
    }

    public static function getTahapanDisplay()
    {
        return [
            self::TAHAPAN_BELUM_DIPROSES => '⏳ ' . self::TAHAPAN_BELUM_DIPROSES,
            self::TAHAPAN_PROJECT_TO_EPC => '🔄 ' . self::TAHAPAN_PROJECT_TO_EPC,
            self::TAHAPAN_PMO_TO_EPC => '📋 ' . self::TAHAPAN_PMO_TO_EPC,
            self::TAHAPAN_EPC_TO_PROCUREMENT => '✅ ' . self::TAHAPAN_EPC_TO_PROCUREMENT,
        ];
    }

    public function getProgressPercentage()
    {
        return match($this->namaTahapan) {
            self::TAHAPAN_BELUM_DIPROSES => 0,
            self::TAHAPAN_PROJECT_TO_EPC => 33.33,
            self::TAHAPAN_PMO_TO_EPC => 66.66,
            self::TAHAPAN_EPC_TO_PROCUREMENT => 100,
            default => 0
        };
    }

    public function getTahapanColor()
    {
        return match($this->namaTahapan) {
            self::TAHAPAN_BELUM_DIPROSES => 'gray',
            self::TAHAPAN_PROJECT_TO_EPC => 'blue',
            self::TAHAPAN_PMO_TO_EPC => 'yellow',
            self::TAHAPAN_EPC_TO_PROCUREMENT => 'green',
            default => 'gray'
        };
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'no_request', 'no_request');
    }
}