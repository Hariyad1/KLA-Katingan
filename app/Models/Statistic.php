<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    protected $table = 'statistic';
    
    protected $fillable = [
        'ip',
        'os',
        'browser'
    ];

    /**
     * Get visitor count by date range
     */
    public static function getVisitorCount($startDate = null, $endDate = null)
    {
        $query = self::query();
        
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        return $query->count();
    }

    /**
     * Get visitor statistics by browser
     */
    public static function getBrowserStats()
    {
        return self::select('browser')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('browser')
            ->get();
    }

    /**
     * Get visitor statistics by OS
     */
    public static function getOSStats()
    {
        return self::select('os')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('os')
            ->get();
    }
} 