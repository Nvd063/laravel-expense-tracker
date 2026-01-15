<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // Yeh line boht zaroori hai

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Yeh list database mein add hogi
        $categories = ['Food', 'Travel', 'Bills', 'Shopping', 'Others'];

        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }
    }
}