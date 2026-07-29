<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_favorite_an_item()
    {
        $this->withoutMiddleware(EnsureEmailIsVerified::class);

        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('favorite.store', $item));

        $response->assertRedirect();

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('item.show', $item));

        $response->assertStatus(200);
        $response->assertSee('1');
    }

    public function test_favorite_icon_changes_when_item_is_favorited()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('item.show', $item));

        $response->assertStatus(200);
        $response->assertSee('heart-pink.png');
    }

    public function test_user_can_remove_favorite()
    {
        $this->withoutMiddleware(EnsureEmailIsVerified::class);

        $user = User::factory()->create();
        $item = Item::factory()->create();

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('favorite.destroy', $item));

        $response->assertRedirect();

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
