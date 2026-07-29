<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 全商品を取得できる
     */
    public function test_all_items_are_displayed()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    /**
     * 購入済み商品はSoldと表示される
     */
    public function test_sold_item_is_displayed_with_sold_label()
    {
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'name' => '購入済み商品',
        ]);

        Order::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postal_code' => '123-4567',
            'address' => '東京都テスト区1-2-3',
            'building_name' => 'テストビル101',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('購入済み商品');
        $response->assertSee('Sold');
    }

    /**
     * ログインユーザーが出品した商品は表示されない
     */
    public function test_own_item_is_not_displayed()
    {
        $user = User::factory()->create();

        $ownItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
        ]);

        $otherItem = Item::factory()->create([
            'name' => '他のユーザーの商品',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/');

        $response->assertStatus(200);
        $response->assertDontSee($ownItem->name);
        $response->assertSee($otherItem->name);
    }
}
