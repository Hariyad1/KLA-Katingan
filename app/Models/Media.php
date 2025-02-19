<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';
    
    protected $fillable = [
        'name',
        'file',
        'path',
        'slide_show',
        'hits'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'slide_show' => 'boolean',
        'hits' => 'integer'
    ];

    /**
     * slide_show: Status untuk media slideshow (1 = true, 0/null = false)
     * hits: Memunculkan berapa kali file di download
     */
} 