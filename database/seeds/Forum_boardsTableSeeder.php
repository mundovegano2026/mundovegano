<?php

use Illuminate\Database\Seeder;

class Forum_boardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Forum_board::class, 5)->create();
    }
}
