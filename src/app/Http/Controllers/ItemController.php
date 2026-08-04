<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * 商品一覧画面
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');
        $user = Auth::user();

        if ($tab === 'mylist') {
            $items = $user
                ? $user->favoriteItems()
                ->with('order')
                ->when($keyword, function ($query, $keyword) {
                    $query->where(
                        'name',
                        'like',
                        '%' . $keyword . '%'
                    );
                })
                ->latest()
                ->get()
                : collect();
        } else {
            $items = Item::with('order')
                ->when($user, function ($query) use ($user) {
                    $query->where('user_id', '!=', $user->id);
                })
                ->when($keyword, function ($query, $keyword) {
                    $query->where(
                        'name',
                        'like',
                        '%' . $keyword . '%'
                    );
                })
                ->latest()
                ->get();
        }

        return view(
            'item.index',
            compact('items', 'tab', 'keyword')
        );
    }

    /**
     * 商品詳細画面
     */
    public function show(Item $item)
    {
        $item->load([
            'user',
            'comments.user',
            'categories',
            'condition',
            'favorites',
        ]);

        return view('item.show', compact('item'));
    }

    /**
     * 商品出品画面
     */
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view(
            'item.create',
            compact('categories', 'conditions')
        );
    }

    /**
     * 商品出品処理
     */
    public function store(ExhibitionRequest $request)
    {
        $data = $request->validated();

        $data['image_url'] = $request
            ->file('image_url')
            ->store('items', 'public');

        DB::transaction(function () use ($data) {
            $item = Item::create([
                'user_id' => Auth::id(),
                'condition_id' => $data['condition_id'],
                'name' => $data['name'],
                'brand_name' => $data['brand_name'] ?? null,
                'price' => $data['price'],
                'description' => $data['description'],
                'image_url' => $data['image_url'],
            ]);

            $item->categories()->attach($data['categories']);
        });

        return redirect('/')
            ->with('success', '商品を出品しました。');
    }
}
