<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Condition;
use App\Models\User;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExhibitionTest extends TestCase
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
     * 商品出品画面で入力した情報が正しく保存される
     */
    public function test_user_can_create_an_item()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $category1 = Category::create([
            'content' => 'ファッション',
        ]);

        $category2 = Category::create([
            'content' => '家電',
        ]);

        $condition = Condition::create([
            'content' => '良好',
        ]);

        $image = UploadedFile::fake()->create(
            'test-item.png',
            100,
            'image/png'
        );

        $response = $this
            ->actingAs($user)
            ->post(route('item.store'), [
                'name' => '出品テスト商品',
                'description' => '商品の説明テストです。',
                'image_url' => $image,
                'categories' => [
                    $category1->id,
                    $category2->id,
                ],
                'condition_id' => $condition->id,
                'price' => 5000,
                'brand_name' => 'テストブランド',
            ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => '出品テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 5000,
            'description' => '商品の説明テストです。',
        ]);

        $item = \App\Models\Item::where(
            'name',
            '出品テスト商品'
        )->first();

        $this->assertNotNull($item);

        Storage::disk('public')->assertExists(
            $item->image_url
        );

        $this->assertDatabaseHas('category_items', [
            'item_id' => $item->id,
            'category_id' => $category1->id,
        ]);

        $this->assertDatabaseHas('category_items', [
            'item_id' => $item->id,
            'category_id' => $category2->id,
        ]);
    }
}
