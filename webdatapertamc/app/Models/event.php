<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $primaryKey = 'no_I/O';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_I/O',
        'title',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'no_I/O';
    }

    public function tools()
    {
        return $this->hasMany(tools::class, 'event_no_I/O', 'no_I/O');
    }

    public function requestItems()
    {
        return $this->hasMany(RequestItem::class, 'event_no_io', 'no_I/O');
    }
}
