<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blogPostsCount =(int) $this->command->ask('How many blog posts would you like?', 50);
        $users= User::all();
        BlogPost::factory($blogPostsCount)->make()->each(function($post) use ($users){
            $post->user_id = $users->random()->id;
            $post->save();
        });
    }
}
