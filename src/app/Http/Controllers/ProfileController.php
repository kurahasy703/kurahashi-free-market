<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // ほかのメソッドはこの上に残してください

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

        $user = Auth::user();

        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building_name = $request->building_name;
        $user->save();

        return redirect()
            ->route('order.create', ['item' => $item->id])
            ->with('message', '送付先を更新しました。');
    }
}
