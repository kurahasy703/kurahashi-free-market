<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * マイページ
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $items = $user->orders()
                ->with('item.order')
                ->latest()
                ->get()
                ->pluck('item');
        } else {
            $items = $user->items()
                ->with('order')
                ->latest()
                ->get();
        }

        return view(
            'profile.show',
            compact('user', 'items', 'page')
        );
    }

    /**
     * プロフィール編集画面
     */
    public function edit()
    {
        $user = Auth::user();

        return view(
            'profile.edit',
            compact('user')
        );
    }

    /**
     * プロフィール更新
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request
                ->file('profile_image')
                ->store('profiles', 'public');
        } else {
            unset($data['profile_image']);
        }

        $user->update($data);

        return redirect()
            ->route('profile.show')
            ->with('message', 'プロフィールを更新しました。');
    }

    /**
     * 配送先変更画面
     */
    public function editAddress(Item $item)
    {
        $user = Auth::user();

        return view(
            'profile.editAddress',
            compact('user', 'item')
        );
    }

    /**
     * 配送先住所更新
     */
    public function updateAddress(Request $request, Item $item)
    {
        $request->validate(
            [
                'postal_code' => [
                    'required',
                    'regex:/^\d{3}-\d{4}$/',
                ],
                'address' => [
                    'required',
                ],
                'building_name' => [
                    'nullable',
                ],
            ],
            [
                'postal_code.required' =>
                '郵便番号を入力してください。',
                'postal_code.regex' =>
                '郵便番号は「123-4567」の形式で入力してください。',
                'address.required' =>
                '住所を入力してください。',
            ]
        );

        session([
            'shipping_address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building_name' => $request->building_name,
            ],
        ]);

        return redirect()
            ->route('order.create', ['item' => $item->id])
            ->with('address_updated', true)
            ->with('message', '送付先を更新しました。');
    }
}
