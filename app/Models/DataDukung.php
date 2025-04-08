<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataDukung extends Model
{
    use HasFactory;

    protected $table = 'data_dukung';
    
    protected $fillable = [
        'opd_id',
        'indikator_id',
        'description',
        'created_by'
    ];

    public function opd()
    {
        return $this->belongsTo(Opd::class);
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function files()
    {
        return $this->hasMany(DataDukungFile::class);
    }
} 