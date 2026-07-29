<?php

namespace Database\Factories;

use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'condition_id' => Condition::query()->value('id')
                ?? Condition::create([
                    'content' => '良好',
                ])->id,

            'name' => $this->faker->word(),
            'brand_name' => $this->faker->company(),
            'price' => $this->faker->numberBetween(1000, 50000),
            'description' => $this->faker->sentence(),
            'image_url' => 'items/test.jpg',
        ];
    }
}
