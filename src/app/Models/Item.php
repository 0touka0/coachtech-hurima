<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'name',
        'price',
        'description',
        'image_path',
        'condition',
    ];

    // 商品の売り手
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // 商品の購入履歴
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // 商品に対するコメント
    public function itemComments()
    {
        return $this->hasMany(ItemComment::class);
    }

    // 商品のお気に入り
    public function myLists()
    {
        return $this->hasMany(Mylist::class);
    }

    // 商品のカテゴリ（多対多リレーション）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categorizations');
    }
}
