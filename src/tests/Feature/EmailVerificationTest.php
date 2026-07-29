<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 会員登録後に認証メールが送信される
     */
    public function test_verification_email_is_sent_after_registration()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/mypage/profile');

        $user = User::where(
            'email',
            'test@example.com'
        )->first();

        $this->assertNotNull($user);

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    /**
     * メール認証画面が表示される
     */
    public function test_verify_email_page_is_displayed()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/email/verify');

        $response->assertStatus(200);

        $response->assertSee(
            '登録していただいたメールアドレスに認証メールを送付しました。'
        );

        $response->assertSee(
            '認証はこちらから'
        );

        $response->assertSee(
            '認証メールを再送する'
        );

        $response->assertSee(
            'http://localhost:8025',
            false
        );
    }
}
