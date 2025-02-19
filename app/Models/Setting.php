<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'setting';
    
    protected $fillable = [
        'name',
        'page',
        'url',
        'image',
        'content',
        'type'
    ];

    /**
     * Get settings by type
     */
    public static function getByType($type)
    {
        return self::where('type', $type)->get();
    }

    /**
     * Get setting by page
     */
    public static function getByPage($page)
    {
        return self::where('page', $page)->first();
    }
} 