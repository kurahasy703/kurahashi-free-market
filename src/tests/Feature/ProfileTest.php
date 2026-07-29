<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(
            EnsureEmailIsVerified::class
        );
    }

    /**
     * プロフィール画像、ユーザー名、出品した商品が表示される
     */
    public function test_profile_information_and_selling_items_are_displayed()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profiles/test-profile.png',
        ]);

        $sellingItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品テスト商品',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('profile.show', [
                'page' => 'sell',
            ]));

        $response->assertStatus(200);

        $response->assertSee('テストユーザー');
        $response->assertSee('出品テスト商品');

        $response->assertSee(
            'storage/profiles/test-profile.png',
            false
        );

        $response->assertSee(
            route('item.show', $sellingItem),
            false
        );
    }

    /**
     * 購入した商品が表示される
     */
    public function test_purchased_items_are_displayed()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'name' => '購入者テスト',
        ]);

        $purchasedItem = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入済みテスト商品',
        ]);

        Order::create([
            'user_id' => $buyer->id,
            'item_id' => $purchasedItem->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building_name' => 'テストビル101',
            'payment_method' => 'card',
            'stripe_id' => 'cs_test_profile_item',
        ]);

        $response = $this
            ->actingAs($buyer)
            ->get(route('profile.show', [
                'page' => 'buy',
            ]));

        $response->assertStatus(200);

        $response->assertSee('購入者テスト');
        $response->assertSee('購入済みテスト商品');

        $response->assertSee(
            route('item.show', $purchasedItem),
            false
        );
    }
}
