<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Smartphones',
                'description' => 'All types of mobile phones including Android and iOS.'
            ],
            [
                'name' => 'Laptops',
                'description' => 'Portable computers including ultrabooks, gaming laptops, and notebooks.'
            ],
            [
                'name' => 'Tablets',
                'description' => 'Touchscreen tablets for media and work.'
            ],
            [
                'name' => 'Accessories',
                'description' => 'Chargers, cases, headphones, and other accessories.'
            ],
            [
                'name' => 'TV & Audio',
                'description' => 'Televisions, sound systems, and home theater products.'
            ],
            [
                'name' => 'Gaming',
                'description' => 'Gaming consoles, controllers, and gaming accessories.'
            ],
            [
                'name' => 'Networking',
                'description' => 'Routers, switches, and other networking equipment.'
            ]
        ]);
    }
}
