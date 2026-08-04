<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'condition_id',
        'name',
        'brand_name',
        'price',
        'description',
        'image_url',
    ];

    /**
     * 出品者
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 商品状態
     */
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    /**
     * カテゴリー（多対多）
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_items');
    }

    /**
     * コメント
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * お気に入り
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * お気に入り登録したユーザー
     */
    public function favoriteUsers()
    {
        return $this->belongsToMany(
            User::class,
            'favorites',
            'item_id',
            'user_id'
        );
    }

    /**
     * 購入情報
     */
    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
