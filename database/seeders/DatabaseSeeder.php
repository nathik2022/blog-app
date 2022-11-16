<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // DB::table('users')->insert([[
        //     'name' => 'John Doe',
        //     'email' => 'john@laravel.test',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token'=> Str::random(10)
        // ]]);
        // $doe = User::factory()->johnDoe()->create();
        // $else = User::factory(20)->create();

        // $users = $else->concat([$doe]);

        if($this->command->confirm('Do you want to refresh the database?')){
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
        }

        Cache::tags(['blog-post'])->flush();

        $this->call([
            UsersTableSeeder::class, 
            BlogPostsTableSeeder::class,
            CommentsTableSeeder::class,
            TagsTableSeeder::class,
            BlogPostTagTableSeeder::class
        ]);

        // $posts = BlogPost::factory(50)->make()->each(function($post) use ($users){
        //     $post->user_id = $users->random()->id;
        //     $post->save();
        // });

        // $comments = Comment::factory(150)->make()->each(function ($comment) use ($posts){
        //     $comment->blog_post_id = $posts->random()->id;
        //     $comment->save();
        // });
    }
}
