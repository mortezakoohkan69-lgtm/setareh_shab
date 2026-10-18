<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['نوشیدنی', 'کیک و دسر', 'غذا'] as $title) {
            Category::updateOrCreate(['title' => $title, 'type' => 'product'], ['is_active' => true]);
        }

        foreach (['لبنیات', 'قهوه و پودریجات'] as $title) {
            Category::updateOrCreate(['title' => $title, 'type' => 'material'], ['is_active' => true]);
        }
    }
}
