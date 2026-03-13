<?php

use Illuminate\Database\Seeder;

class Forum_postsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Forum_post::class, 30)->create();
    }
}
