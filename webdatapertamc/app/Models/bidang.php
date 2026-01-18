<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class bidang extends Model
{
    use HasFactory;

    protected $table = 'bidangs';
    protected $primaryKey = 'kodeGl';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        'kodeGl',
        'nama_Bidang',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'kodeGl';
    }

    public function tools()
    {
        return $this->hasMany(tools::class, 'Bidang_kodeGl', 'kodeGl');
    }
}
