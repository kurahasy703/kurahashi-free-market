<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item = Item::create([
            'name' => '腕時計',
            'price' => 15000,
            'brand_name' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'image_url' => 'items/Clock.jpg',
            'user_id' => 1,
            'condition_id' => 1,
        ]);

        $item->categories()->attach([5]);

        $item = Item::create([
            'name' => 'HDD',
            'price' => 5000,
            'brand_name' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'image_url' => 'items/Disk.jpg',
            'user_id' => 2,
            'condition_id' => 2,
        ]);

        $item->categories()->attach([2]);

        $item = Item::create([
            'name' => '玉ねぎ３束',
            'price' => 300,
            'brand_name' => 'なし',
            'description' => '新鮮な玉ねぎ３束のセット',
            'image_url' => 'items/onion.jpg',
            'user_id' => 1,
            'condition_id' => 3,
        ]);

        $item->categories()->attach([10]);

        $item = Item::create([
            'name' => '革靴',
            'price' => 4000,
            'brand_name' => '',
            'description' => 'クラシックなデザインの革靴',
            'image_url' => 'items/Shoes.jpg',
            'user_id' => 2,
            'condition_id' => 4,
        ]);

        $item->categories()->attach([5]);

        $item = Item::create([
            'name' => 'ノートPC',
            'price' => 45000,
            'brand_name' => '',
            'description' => '高性能なノートパソコン',
            'image_url' => 'items/PC.jpg',
            'user_id' => 1,
            'condition_id' => 1,
        ]);

        $item->categories()->attach([2]);

        $item = Item::create([
            'name' => 'マイク',
            'price' => 8000,
            'brand_name' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'image_url' => 'items/Mic.jpg',
            'user_id' => 1,
            'condition_id' => 2,
        ]);

        $item->categories()->attach([2]);

        $item = Item::create([
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand_name' => '',
            'description' => 'おしゃれなショルダーバッグ',
            'image_url' => 'items/bag.jpg',
            'user_id' => 1,
            'condition_id' => 3,
        ]);

        $item->categories()->attach([4]);

        $item = Item::create([
            'name' => 'タンブラー',
            'price' => 500,
            'brand_name' => 'なし',
            'description' => '使いやすいタンブラー',
            'image_url' => 'items/Tumbler.jpg',
            'user_id' => 1,
            'condition_id' => 4,
        ]);

        $item->categories()->attach([9]);

        $item = Item::create([
            'name' => 'コーヒーミル',
            'price' => 4000,
            'brand_name' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'image_url' => 'items/CoffeeGrinder.jpg',
            'user_id' => 1,
            'condition_id' => 1,
        ]);

        $item->categories()->attach([9]);

        $item = Item::create([
            'name' => 'メイクセット',
            'price' => 2500,
            'brand_name' => '',
            'description' => '便利なメイクアップセット',
            'image_url' => 'items/makeup.jpg',
            'user_id' => 1,
            'condition_id' => 2,
        ]);

        $item->categories()->attach([4]);
    }
}
