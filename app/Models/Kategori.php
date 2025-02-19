<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    
    protected $fillable = [
        'name'
    ];

    /**
     * Get the news for the kategori.
     */
    public function news()
    {
        return $this->hasMany(News::class, 'kategori_id');
    }
} 