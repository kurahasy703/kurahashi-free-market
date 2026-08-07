<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Favorite;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品詳細ページに必要な情報が表示される
     */
    public function test_item_detail_page_displays_all_required_information()
    {
        $seller = User::factory()->create([
            'name' => '出品者ユーザー',
        ]);

        $commentUser = User::factory()->create([
            'name' => 'コメントユーザー',
        ]);

        $favoriteUser1 = User::factory()->create();
        $favoriteUser2 = User::factory()->create();

        $condition = Condition::create([
            'content' => '良好',
        ]);

        $category = Category::create([
            'content' => 'ファッション',
        ]);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '腕時計',
            'brand_name' => 'テストブランド',
            'price' => 15000,
            'description' => '商品の詳しい説明です。',
            'image_url' => 'items/test-watch.jpg',
        ]);

        $item->categories()->attach($category->id);

        Favorite::create([
            'user_id' => $favoriteUser1->id,
            'item_id' => $item->id,
        ]);

        Favorite::create([
            'user_id' => $favoriteUser2->id,
            'item_id' => $item->id,
        ]);

        Comment::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'content' => 'こちらの商品はまだ購入できますか？',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);

        $response->assertSee('腕時計');
        $response->assertSee('テストブランド');
        $response->assertSee('15,000');
        $response->assertSee('商品の詳しい説明です。');
        $response->assertSee('ファッション');
        $response->assertSee('良好');

        $response->assertSee('items/test-watch.jpg');

        $response->assertSee('2');

        $response->assertSee('コメントユーザー');
        $response->assertSee('こちらの商品はまだ購入できますか？');
    }

    /**
     * 複数選択されたカテゴリがすべて表示される
     */
    public function test_multiple_categories_are_displayed()
    {
        $condition = Condition::create([
            'content' => '目立った傷や汚れなし',
        ]);

        $item = Item::factory()->create([
            'condition_id' => $condition->id,
            'name' => 'カテゴリ確認用商品',
        ]);

        $category1 = Category::create([
            'content' => 'ファッション',
        ]);

        $category2 = Category::create([
            'content' => 'メンズ',
        ]);

        $category3 = Category::create([
            'content' => 'アクセサリー',
        ]);

        $item->categories()->attach([
            $category1->id,
            $category2->id,
            $category3->id,
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('ファッション');
        $response->assertSee('メンズ');
        $response->assertSee('アクセサリー');
    }
}
