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
        'browser',
        'last_activity'
    ];

    protected $casts = [
        'last_activity' => 'datetime'
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
    }    public static function updateActivity($ip)
    {
        $record = self::where('ip', $ip)
            ->whereDate('created_at', today())
            ->first();
        
        if ($record) {
            $record->last_activity = now();
            $record->save();
        }
        
        return true;
    }

    public static function getOnlineVisitors()
    {
        return self::where('last_activity', '>=', now()->subMinutes(5))
            ->count();
    }
} 