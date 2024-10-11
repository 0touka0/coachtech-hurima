<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'buyer_id',
        'payment_method',
        'delivery_postal_code',
        'delivery_address',
        'delivery_building_name',
    ];

    // この取引に関連するアイテム
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // この取引の買い手
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
