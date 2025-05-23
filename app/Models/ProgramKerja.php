<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'opd_id',
        'description',
        'tahun'
    ];

    public function opd()
    {
        return $this->belongsTo(Opd::class);
    }
} 