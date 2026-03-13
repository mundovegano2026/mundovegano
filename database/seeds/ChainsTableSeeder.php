<?php

use Illuminate\Database\Seeder;

class ChainsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Chain::class, 30)->create();
    }
}
