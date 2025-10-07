<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    // Mendefinisikan Primary Key karena bukan 'id'
    protected $primaryKey = 'kode_GL';

    // Memberitahu Laravel bahwa Primary Key bukan auto-incrementing
    public $incrementing = false;

    // Tipe data primary key adalah string
    protected $keyType = 'string';

    // Atribut yang boleh diisi secara massal
    protected $fillable = [
        'kode_GL',
        'nama_Bidang',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'kode_GL';
    }

    /**
     * Relasi One to Many dengan Tools
     */
    public function tools()
    {
        return $this->hasMany(Tool::class, 'kode_GL', 'kode_GL');
    }
}
