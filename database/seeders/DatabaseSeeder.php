<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Valuestore\Valuestore;
use App\Models\Item;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create();

        /** 
         * Items
         */
        Item::truncate();
        $items =  [
            // Burger Category
            [
              'item' => 'Milk',
              'description' => 'Lorem Ipsum is simply dummy text.',
              'price' => 50,
              'balance' => 50,
              'category' => 'milk',
            ],
            [
                'item' => 'Choco',
                'description' => 'Lorem Ipsum is simply dummy text.',
                'price' => 50,
                'balance' => 100,
                'category' => 'choco',
              ],
        ];

        foreach($items as $item){
            Item::create($item);
        } 


        /** 
         * Settings
         */
        $valueStore = Valuestore::make(storage_path('app/settings.json'));
        $valueStore->put('items_category', 'milk, choco');

    }
}
