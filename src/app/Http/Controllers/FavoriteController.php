<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * お気に入り登録
     */
    public function store(Item $item)
    {
        Auth::user()
            ->favorites()
            ->firstOrCreate([
                'item_id' => $item->id,
            ]);

        return back();
    }

    /**
     * お気に入り解除
     */
    public function destroy(Item $item)
    {
        Auth::user()
            ->favorites()
            ->where('item_id', $item->id)
            ->delete();

        return back();
    }
}
