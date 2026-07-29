<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Item $item)
    {
        Auth::user()->favorites()->firstOrCreate([
            'item_id' => $item->id,
        ]);

        return back();
    }

    public function destroy(Item $item)
    {
        Auth::user()->favorites()
            ->where('item_id', $item->id)
            ->delete();

        return back();
    }
}
