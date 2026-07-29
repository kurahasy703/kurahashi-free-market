<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
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
     * 購入画面に支払い方法の選択肢が表示される
     */
    public function test_payment_method_options_are_displayed()
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

        $response = $this
            ->actingAs($buyer)
            ->get(route('order.create', $item));

        $response->assertStatus(200);

        $response->assertSee(
            'name="payment_method"',
            false
        );

        $response->assertSee(
            'value="konbini"',
            false
        );

        $response->assertSee(
            'value="card"',
            false
        );

        $response->assertSee('コンビニ支払い');
        $response->assertSee('カード支払い');
    }

    /**
     * 選択した支払い方法を小計欄に反映する構造がある
     */
    public function test_selected_payment_method_can_be_reflected_in_summary()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $response = $this
            ->actingAs($buyer)
            ->get(route('order.create', $item));

        $response->assertStatus(200);

        // プルダウンのID
        $response->assertSee(
            'id="payment_method"',
            false
        );

        // 小計欄の反映先
        $response->assertSee(
            'id="selected-payment-method"',
            false
        );

        // JavaScriptの表示内容
        $response->assertSee(
            "konbini: 'コンビニ支払い'",
            false
        );

        $response->assertSee(
            "card: 'カード支払い'",
            false
        );

        // 選択変更時の処理
        $response->assertSee(
            "paymentSelect.addEventListener",
            false
        );

        $response->assertSee(
            "'change'",
            false
        );

        $response->assertSee(
            'updatePaymentDisplay',
            false
        );
    }
}
