<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    use HasFactory;

    protected $fillable = [
        'klaster_id',
        'name'
    ];

    public function klaster()
    {
        return $this->belongsTo(Klaster::class);
    }

    public function dataDukungs()
    {
        return $this->hasMany(DataDukung::class);
    }
} 