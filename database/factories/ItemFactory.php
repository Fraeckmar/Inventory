<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Valuestore\Valuestore;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rand_digit = rand(0, 6);
        $items = [
            [
                'item' => 'Milk',
                'description' => 'Creamy Milk',
                'price' => 10,
                'balance' => 111,
                'category' => 'milk',
            ],
            [
                'item' => 'Choco',
                'description' => 'Tasty Choco',
                'price' => 20,
                'balance' => 111,
                'category' => 'choco',
            ],
            [
                'item' => 'Vanilla',
                'description' => 'Icy Vanilla',
                'price' => 30,
                'balance' => 111,
                'category' => 'vanila',
            ],
            [
                'item' => 'Melon',
                'description' => 'Sweet Melon',
                'price' => 40,
                'balance' => 111,
                'category' => 'melon',
            ],
            [
                'item' => 'Lemon',
                'description' => 'Sour and Sweet Lemon',
                'price' => 50,
                'balance' => 111,
                'category' => 'lemon',
            ],
            [
                'item' => 'Banana',
                'description' => 'Healty Banana',
                'price' => 60,
                'balance' => 111,
                'category' => 'banana',
            ],
            [
                'item' => 'Cheese',
                'description' => 'Sticky cheese',
                'price' => 70,
                'balance' => 111,
                'category' => 'cheese',
            ]
        ];
        $items_category = array_column($items, 'category');
        $items_category = implode(',', $items_category);

        $valueStore = Valuestore::make(storage_path('app/settings.json'));
        $items_category = $valueStore->get('items_category');
        if (empty($items_category)) {
            $valueStore->put('items_category', $items_category);
        }

        return $items[$rand_digit];
    }
}
