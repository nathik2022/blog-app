<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Observers\BlogPostObserver;
use App\Observers\CommentObserver;
use App\Services\Counter;
use App\Services\DummyCounter;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Blade::aliasComponent('components.badge','badge');
        Blade::aliasComponent('components.updated','updated');
        Blade::aliasComponent('components.card','card');
        Blade::aliasComponent('components.tags','tags');
        Blade::aliasComponent('components.errors','errors');
        Blade::aliasComponent('components.comment-form','commentForm');
        Blade::aliasComponent('components.comment-list','commentList');


        //view()->composer('*',ActivityComposer::class);
        view()->composer(['posts.index','posts.show'],ActivityComposer::class);
        BlogPost::observe(BlogPostObserver::class);
        Comment::observe((CommentObserver::class));

        $this->app->singleton(Counter::class, function ($app){
            return new Counter(
                $app->make('Illuminate\Support\Facades\Cache'),
                $app->make('Illuminate\Contracts\Session\Session'),
                intval(env('COUNTER_TIMEOUT'))
            );
        });

        $this->app->bind(
            'App\Contracts\CounterContract',
            Counter::class
        );

        // $this->app->bind(
        //     'App\Contracts\CounterContract',
        //     DummyCounter::class
        // );
        // $this->app->when(Counter::class)
        // ->needs('$timeout')
        // ->give(env('COUNTER_TIMEOUT'));

        //CommentResource::withoutWrapping();
        JsonResource::withoutWrapping();
    }
}
