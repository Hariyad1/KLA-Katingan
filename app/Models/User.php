<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\products;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'email_verified_at' => 'datetime',
        ];
    }

    // TODO: Add forieng-key relation
    public function products()
    {
        return $this->hasMany(products::class);
    }

    /**
     * User status constants
     * status: 1=admin, 0=user
     */
    const STATUS_ADMIN = 1;
    const STATUS_USER = 0;

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->status === self::STATUS_ADMIN;
    }

    /**
     * Get the news created by the user
     */
    public function news()
    {
        return $this->hasMany(News::class, 'created_by');
    }
}
