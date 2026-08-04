<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class OrderController extends Controller
{
    /**
     * 購入画面
     */
    public function create(Item $item)
    {
        $user = Auth::user();

        return view('order.create', compact('item', 'user'));
    }

    /**
     * 注文情報を保存してStripeの決済画面へ移動
     */
    public function store(Request $request, Item $item)
    {
        $request->validate(
            [
                'payment_method' => [
                    'required',
                    'in:konbini,card',
                ],
            ],
            [
                'payment_method.required' =>
                '支払い方法を選択してください。',
                'payment_method.in' =>
                '正しい支払い方法を選択してください。',
            ]
        );

        if ($item->order()->exists()) {
            return redirect()
                ->route('item.show', $item)
                ->with(
                    'error',
                    'この商品はすでに購入されています。'
                );
        }

        $user = Auth::user();

        /*
         * Stripeへ移動する前に、
         * 配送情報をordersテーブルへ保存
         */
        $order = Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building_name' => $user->building_name,
            'payment_method' => $request->payment_method,
            'stripe_id' => null,
        ]);

        try {
            Stripe::setApiKey(
                config('services.stripe.secret')
            );

            $session = Session::create([
                'payment_method_types' => [
                    $request->payment_method,
                ],

                'mode' => 'payment',

                'customer_email' => $user->email,

                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'jpy',

                            'product_data' => [
                                'name' => $item->name,
                            ],

                            'unit_amount' => $item->price,
                        ],

                        'quantity' => 1,
                    ],
                ],

                'metadata' => [
                    'order_id' => (string) $order->id,
                    'item_id' => (string) $item->id,
                    'user_id' => (string) $user->id,
                    'payment_method' =>
                    $request->payment_method,
                ],

                'success_url' => route(
                    'order.success',
                    ['item' => $item]
                ) . '?session_id={CHECKOUT_SESSION_ID}',

                'cancel_url' => route(
                    'order.create',
                    ['item' => $item]
                ),
            ]);

            /*
             * 作成されたStripeセッションIDを保存
             */
            $order->update([
                'stripe_id' => $session->id,
            ]);

            return redirect()->away($session->url);
        } catch (ApiErrorException $e) {
            /*
             * Stripe決済画面を作れなかった場合は、
             * 先に作った注文データを削除
             */
            $order->delete();

            return redirect()
                ->route('order.create', $item)
                ->with(
                    'error',
                    '決済画面を作成できませんでした。'
                );
        }
    }

    /**
     * Stripe決済後の処理
     */
    /**
     * Stripe決済後の処理
     */
    public function success(Request $request, Item $item)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()
                ->route('order.create', $item)
                ->with(
                    'error',
                    '決済情報を確認できませんでした。'
                );
        }

        try {
            Stripe::setApiKey(
                config('services.stripe.secret')
            );

            $session = Session::retrieve($sessionId);
        } catch (ApiErrorException $e) {
            return redirect()
                ->route('order.create', $item)
                ->with(
                    'error',
                    '決済情報を確認できませんでした。'
                );
        }

        if (
            (int) $session->metadata->item_id
            !== $item->id
        ) {
            abort(403);
        }

        if (
            (int) $session->metadata->user_id
            !== Auth::id()
        ) {
            abort(403);
        }

        /*
     * store()で作成済みの注文情報を確認
     */
        $order = Order::where(
            'id',
            $session->metadata->order_id
        )
            ->where('item_id', $item->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        /*
     * StripeセッションIDを保存
     */
        $order->update([
            'stripe_id' => $session->id,
        ]);

        return redirect()
            ->route(
                'profile.show',
                ['page' => 'buy']
            )
            ->with(
                'success',
                '購入手続きが完了しました。'
            );
    }
}
