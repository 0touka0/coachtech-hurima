<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'is_favorited',
    ];

    // お気に入りに追加したユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // お気に入りに追加されたアイテム
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
