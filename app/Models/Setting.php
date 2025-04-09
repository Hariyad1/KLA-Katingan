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

    /**
     * Get YouTube embed URL
     */
    public function getEmbedUrlAttribute()
    {
        if (!$this->url) {
            return null;
        }
        
        if (strpos($this->url, 'youtube.com/embed/') !== false) {
            return $this->url;
        }
        
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        if (preg_match($pattern, $this->url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        
        return $this->url;
    }
} 