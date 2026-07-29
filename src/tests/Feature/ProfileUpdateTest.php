<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
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
     * プロフィール編集画面に登録済み情報が初期値として表示される
     */
    public function test_profile_edit_page_displays_current_user_information()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profiles/test-user.png',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building_name' => 'テストビル101',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('profile.edit'));

        $response->assertStatus(200);

        // プロフィール画像
        $response->assertSee(
            'storage/profiles/test-user.png',
            false
        );

        // ユーザー名
        $response->assertSee(
            'value="テストユーザー"',
            false
        );

        // 郵便番号
        $response->assertSee(
            'value="123-4567"',
            false
        );

        // 住所
        $response->assertSee(
            'value="東京都渋谷区1-2-3"',
            false
        );

        // 建物名
        $response->assertSee(
            'value="テストビル101"',
            false
        );
    }
}
