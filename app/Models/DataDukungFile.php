<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDukungFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_dukung_id',
        'file',
        'original_name',
        'mime_type',
        'size'
    ];

    public function dataDukung()
    {
        return $this->belongsTo(DataDukung::class);
    }
} 