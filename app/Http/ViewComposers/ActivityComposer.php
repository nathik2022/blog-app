<?php

namespace App\Http\ViewComposers;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ActivityComposer
{

    public function compose(View $view)
    {

        $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-most-commented',now()->addSeconds(20),function (){
            return BlogPost::mostCommented()->take(5)->get();
        });

        $mostActive = Cache::tags(['blog-post'])->remember('user-most-active',now()->addSeconds(20),function (){
            return User::mostBlogPosts()->take(5)->get();
        });

        $mostActiveLastMonth= Cache::tags(['blog-post'])->remember('most-active-last-month',now()->addSeconds(20),function (){
            return User::mostBlogPostsLastMonth()->take(5)->get();
        });

        $view->with('mostCommented',$mostCommented);
        $view->with('mostActive',$mostActive);
        $view->with('mostActiveLastMonth',$mostActiveLastMonth);
    }

}