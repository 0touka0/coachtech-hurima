<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ユーザーが売った取引
    public function soldTransactions()
    {
        return $this->hasMany(Transaction::class, 'seller_id');
    }

    // ユーザーが買った取引
    public function boughtTransactions()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    // ユーザーのコメント
    public function itemComments()
    {
        return $this->hasMany(ItemComment::class);
    }

    // ユーザーのお気に入りリスト
    public function myLists()
    {
        return $this->hasMany(Mylist::class);
    }
}
