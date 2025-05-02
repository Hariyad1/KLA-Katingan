<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opd extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function dataDukung()
    {
        return $this->hasMany(DataDukung::class);
    }
    
    public function programKerjas()
    {
        return $this->hasMany(ProgramKerja::class);
    }
} 