<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(
            EnsureEmailIsVerified::class
        );

        config([
            'services.stripe.secret' => 'sk_test_dummy',
        ]);
    }

    /**
     * Stripe決済成功後に購入情報が保存される
     */
    /**
     * Stripe決済成功後に購入情報が更新される
     */
    public function test_user_can_complete_purchase()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building_name' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        /*
     * Stripeへ移動する前に作成される注文情報
     */
        $order = Order::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building_name' => 'テストビル101',
            'payment_method' => 'card',
            'stripe_id' => null,
        ]);

        $stripeSession = (object) [
            'id' => 'cs_test_123',
            'metadata' => (object) [
                'order_id' => (string) $order->id,
                'item_id' => (string) $item->id,
                'user_id' => (string) $buyer->id,
                'payment_method' => 'card',
            ],
        ];

        Mockery::mock('alias:Stripe\Checkout\Session')
            ->shouldReceive('retrieve')
            ->once()
            ->with('cs_test_123')
            ->andReturn($stripeSession);

        $response = $this
            ->actingAs($buyer)
            ->get(
                route('order.success', $item)
                    . '?session_id=cs_test_123'
            );

        $response->assertRedirect(
            route('profile.show', ['page' => 'buy'])
        );

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building_name' => 'テストビル101',
            'payment_method' => 'card',
            'stripe_id' => 'cs_test_123',
        ]);
    }

    /**
     * 購入済み商品は商品一覧でSoldと表示される
     */
    public function test_purchased_item_is_displayed_as_sold()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入済みテスト商品',
        ]);

        Order::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building_name' => null,
            'payment_method' => 'card',
            'stripe_id' => 'cs_test_sold',
        ]);

        $response = $this->get(route('item.index'));

        $response->assertStatus(200);
        $response->assertSee('購入済みテスト商品');
        $response->assertSee('Sold');
    }

    /**
     * 購入した商品がマイページの購入商品一覧に表示される
     */
    public function test_purchased_item_is_displayed_on_profile()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'マイページ表示テスト商品',
        ]);

        Order::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building_name' => null,
            'payment_method' => 'card',
            'stripe_id' => 'cs_test_profile',
        ]);

        $response = $this
            ->actingAs($buyer)
            ->get(route('profile.show', [
                'page' => 'buy',
            ]));

        $response->assertStatus(200);
        $response->assertSee('マイページ表示テスト商品');
    }
}
