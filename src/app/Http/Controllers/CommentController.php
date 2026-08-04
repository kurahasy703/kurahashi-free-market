<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * コメント投稿
     */
    public function store(Request $request, Item $item)
    {
        $request->validate(
            [
                'content' => 'required|max:255',
            ],
            [
                'content.required' =>
                'コメントを入力してください。',
                'content.max' =>
                'コメントは255文字以内で入力してください。',
            ]
        );

        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'content' => $request->content,
        ]);

        return redirect()
            ->route('item.show', $item);
    }
}
