<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    
    protected $fillable = [
        'kategori_id',
        'title',
        'content',
        'image',
        'created_by',
        'counter',
        'flag'
    ];

    protected $casts = [
        'kategori_id' => 'integer',
        'created_by' => 'integer',
        'counter' => 'integer'
    ];

    /**
     * Get the kategori that owns the news.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Get the user that created the news.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The indexes that should be created.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Indeks sudah dibuat di migrasi/database, 
        // tapi kita dokumentasikan di model
        // news_created_by_index
        // news_kategori_id_index
    }
} 