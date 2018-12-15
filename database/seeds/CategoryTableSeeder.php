<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $now = \Illuminate\Support\Carbon::now()->toDateTimeString();
       \App\Category::insert([
           ['name' => 'Laptops',        'slug' => 'laptops',          'created_at' => $now, 'updated_at' => $now],
           ['name' => 'Desktops',       'slug' => 'desktops',         'created_at' => $now, 'updated_at' => $now],
           ['name' => 'Mobile Phones',  'slug' => 'mobile-phones',    'created_at' => $now, 'updated_at' => $now],
           ['name' => 'Taplets',        'slug' => 'taplets',          'created_at' => $now, 'updated_at' => $now],
           ['name' => 'Tvs',            'slug' => 'tvs',              'created_at' => $now, 'updated_at' => $now],
           ['name' => 'Digital cameras','slug' => 'digital-cameras',  'created_at' => $now, 'updated_at' => $now],
           ['name' => 'Applications',   'slug' => 'applications',     'created_at' => $now, 'updated_at' => $now],
       ]);

    }
}
