<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function indikators()
    {
        return $this->hasMany(Indikator::class);
    }
}