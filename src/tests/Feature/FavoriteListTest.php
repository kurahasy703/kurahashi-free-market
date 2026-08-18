<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいねした商品だけがマイリストに表示される
     */
    public function test_only_favorited_items_are_displayed()
    {
        $user = User::factory()->create();

        $favoriteItem = Item::factory()->create([
            'name' => 'いいねした商品',
        ]);

        $otherItem = Item::factory()->create([
            'name' => 'いいねしていない商品',
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $favoriteItem->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('いいねした商品');
        $response->assertDontSee('いいねしていない商品');
    }

    /**
     * 購入済みのいいね商品にはSoldと表示される
     */
    public function test_purchased_favorite_item_is_displayed_as_sold()
    {
        $user = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'name' => '購入済みいいね商品',
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Order::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building_name' => null,
            'payment_method' => 'card',
            'stripe_id' => 'cs_test_favorite_sold',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('購入済みいいね商品');
        $response->assertSee('Sold');
    }
}
