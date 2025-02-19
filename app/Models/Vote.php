<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'votes';
    
    protected $fillable = [
        'nilai_vote',
        'data_pengguna'
    ];

    protected $casts = [
        'nilai_vote' => 'integer',
        'data_pengguna' => 'array'
    ];

    /**
     * Vote value constants
     * 1 = Biasa saja
     * 2 = Bagus
     * 3 = Sangat Bagus
     */
    const BIASA_SAJA = 1;
    const BAGUS = 2;
    const SANGAT_BAGUS = 3;

    /**
     * Get vote label
     */
    public function getVoteLabel()
    {
        return match ($this->nilai_vote) {
            self::BIASA_SAJA => 'Biasa saja',
            self::BAGUS => 'Bagus',
            self::SANGAT_BAGUS => 'Sangat Bagus',
            default => 'Tidak Valid'
        };
    }

    /**
     * Get vote statistics
     */
    public static function getVoteStats()
    {
        return self::selectRaw('nilai_vote, COUNT(*) as total')
            ->groupBy('nilai_vote')
            ->get()
            ->mapWithKeys(function ($item) {
                $label = match ($item->nilai_vote) {
                    self::BIASA_SAJA => 'Biasa saja',
                    self::BAGUS => 'Bagus',
                    self::SANGAT_BAGUS => 'Sangat Bagus',
                    default => 'Tidak Valid'
                };
                return [$label => $item->total];
            });
    }
} 