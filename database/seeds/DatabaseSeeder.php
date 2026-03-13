<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProductsTableSeeder::class);
        $this->call(BrandsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ChainsTableSeeder::class);
        $this->call(Forum_boardsTableSeeder::class);
        $this->call(Forum_postsTableSeeder::class);
        $this->call(ImagesTableSeeder::class);
        $this->call(ReviewsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(StoresTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(TypesTableSeeder::class);
    }
}
