<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
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
     * 変更した住所が購入画面に反映される
     */
    public function test_updated_address_is_displayed_on_purchase_page()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'postal_code' => '111-1111',
            'address' => '東京都新宿区',
            'building_name' => '旧ビル',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($buyer)->post(
            route('profile.address.update', $item),
            [
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区1-2-3',
                'building_name' => 'テストビル101',
            ]
        );

        $response = $this
            ->actingAs($buyer)
            ->get(route('order.create', $item));

        $response->assertStatus(200);

        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-2-3');
        $response->assertSee('テストビル101');
    }

    /**
     * 配送先を変更してもプロフィール住所は変更されない
     */
    public function test_profile_address_is_not_updated()
    {
        $seller = User::factory()->create();

        $buyer = User::factory()->create([
            'postal_code' => '111-1111',
            'address' => '東京都新宿区',
            'building_name' => '旧ビル',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($buyer)->post(
            route('profile.address.update', $item),
            [
                'postal_code' => '987-6543',
                'address' => '大阪府大阪市1-2-3',
                'building_name' => 'サンプルマンション',
            ]
        );

        $this->assertDatabaseHas('users', [
            'id' => $buyer->id,
            'postal_code' => '111-1111',
            'address' => '東京都新宿区',
            'building_name' => '旧ビル',
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $buyer->id,
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市1-2-3',
            'building_name' => 'サンプルマンション',
        ]);
    }
}
