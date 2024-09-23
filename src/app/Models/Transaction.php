<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'seller_id',
        'buyer_id',
    ];

    // この取引に関連するアイテム
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // この取引の売り手
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // この取引の買い手
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
