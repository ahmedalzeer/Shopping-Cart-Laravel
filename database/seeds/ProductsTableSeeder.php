<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<20;$i++)
        {
            \App\Product::create([
                'name'=>'laptop '.$i,
                'slug'=>'laptop-'.$i,
                'details'=>[13,15,17][array_rand([13,15,17])].'inch, '.[1,2,3][array_rand([1,2,3])].' TB SSD, 32 GB Ram',
                'price' => rand(1000,10000),
                'description'=>'lorem '.$i.' ipsum dolor sit amet, consectetur adipisicing elit. Ipsum temporibus iusto ipsa, asperiores voluptas unde aspernatur praesentium in? Aliquam, dolore!'
            ]);
        }
    }
}
