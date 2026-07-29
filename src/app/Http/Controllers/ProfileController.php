<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // マイページ表示
    public function show(Request $request)
    {
        $user = Auth::user();

        if ($request->page === 'buy') {
            $items = $user->orders->pluck('item');
        } else {
            $items = $user->items;
        }

        return view('profile.show', compact('user', 'items'));
    }

    // プロフィール編集画面
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    // プロフィール更新
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        $user->name = $data['name'];
        $user->postal_code = $data['postal_code'];
        $user->address = $data['address'];
        $user->building_name = $data['building_name'] ?? null;

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('message', 'プロフィールを更新しました');
    }

    // 配送先住所変更画面
    public function editAddress(Item $item)
    {
        $user = Auth::user();

        return view('profile.editAddress', compact('user', 'item'));
    }

    // 配送先住所更新
    public function updateAddress(ProfileRequest $request, Item $item)
    {
        $user = Auth::user();
        $data = $request->validated();

        $user->postal_code = $data['postal_code'];
        $user->address = $data['address'];
        $user->building_name = $data['building_name'] ?? null;

        $user->save();

        return redirect()
            ->route('order.create', $item)
            ->with('message', '送付先を更新しました。');
    }
}
