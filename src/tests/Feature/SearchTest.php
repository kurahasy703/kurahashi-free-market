<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品名で部分一致検索ができる
     */
    public function test_items_can_be_searched_by_partial_name()
    {
        Item::factory()->create([
            'name' => '腕時計',
        ]);

        Item::factory()->create([
            'name' => 'ノートパソコン',
        ]);

        $response = $this->get('/?keyword=時計');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('ノートパソコン');
    }

    /**
     * 検索状態がマイリストでも保持される
     */
    public function test_keyword_is_kept_when_switching_to_mylist()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'name' => '腕時計',
        ]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/?tab=mylist&keyword=時計');

        $response->assertStatus(200);

        $response->assertSee('腕時計');

        // 検索ボックスにキーワードが保持されている
        $response->assertSee('value="時計"', false);
    }
}
