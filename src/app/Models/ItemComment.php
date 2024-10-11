<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'comment',
    ];

    // このコメントを書いたユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // このコメントが書かれたアイテム
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
