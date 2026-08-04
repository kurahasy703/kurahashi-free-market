<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'postal_code',
        'address',
        'building_name',
        'payment_method',
        'stripe_id',
    ];

    /**
     * 購入者
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 購入商品
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
