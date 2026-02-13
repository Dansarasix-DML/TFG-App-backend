<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Blog;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Event;
use App\Models\Product;
use App\Models\Product_Type;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(100)->create();
        Blog::factory(50)->create();
        Post::factory(100)->create();
        Comment::factory(100)->create();
        Event::factory(100)->create();
        Product_Type::factory(100)->create();
        Product::factory(100)->create();
    }
}
