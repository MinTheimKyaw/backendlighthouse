<?php

namespace Database\Seeders;
use App\Models\Post;
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
        Post::factory()->count(40)->create();
        // \App\Models\User::factory(10)->create();
    }
}
