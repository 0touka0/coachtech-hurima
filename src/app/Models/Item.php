<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'image_path',
        'condition',
    ];

    // アイテムの売買履歴
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // アイテムに対するコメント
    public function itemComments()
    {
        return $this->hasMany(ItemComment::class);
    }

    // アイテムのお気に入り
    public function myLists()
    {
        return $this->hasMany(MyList::class);
    }

    // アイテムのカテゴリ（多対多リレーション）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categorizations');
    }
}
