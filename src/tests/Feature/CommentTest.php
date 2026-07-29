<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(
            \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class
        );
    }

    public function test_authenticated_user_can_post_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('comment.store', $item), [
                'content' => 'こちらの商品を購入したいです。',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'こちらの商品を購入したいです。',
        ]);

        $response = $this->get(route('item.show', $item));

        $response->assertStatus(200);
        $response->assertSee('こちらの商品を購入したいです。');
        $response->assertSee('1');
    }

    /**
     * 未ログインのユーザーはコメントを送信できない
     */
    public function test_guest_cannot_post_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('comment.store', $item), [
            'content' => '未ログインユーザーのコメント',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'content' => '未ログインユーザーのコメント',
        ]);
    }

    /**
     * コメントが未入力の場合はバリデーションエラーになる
     */
    public function test_comment_is_required()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('item.show', $item))
            ->post(route('comment.store', $item), [
                'content' => '',
            ]);

        $response->assertRedirect(route('item.show', $item));
        $response->assertSessionHasErrors([
            'content',
        ]);

        $this->assertDatabaseCount('comments', 0);
    }

    /**
     * コメントが255文字を超える場合はバリデーションエラーになる
     */
    public function test_comment_must_not_exceed_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('item.show', $item))
            ->post(route('comment.store', $item), [
                'content' => str_repeat('あ', 256),
            ]);

        $response->assertRedirect(route('item.show', $item));
        $response->assertSessionHasErrors([
            'content',
        ]);

        $this->assertDatabaseCount('comments', 0);
    }
}
