<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
    ];

    // このカテゴリに属するアイテム（多対多リレーション）
    public function items()
    {
        return $this->belongsToMany(Item::class, 'categorizations');
    }
}
