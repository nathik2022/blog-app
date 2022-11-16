<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function blogPosts()
    {
        //return $this->hasMany('App\Model\BlogPost');
        //return $this->hasMany('App\Model\BlogPost');
        //return $this->hasMany('App\Models\Comment');
        return $this->hasMany(BlogPost::class);

    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentsOn(){

        return $this->morphMany('App\Models\Comment','commentable')->lastest();

    }

    public function image()
    {
        return $this->morphOne('App\Models\Image','imageable');
    }

    public function scopeMostBlogPosts( Builder $query)
    {
        return $query->withCount('blogPosts')->orderBy('blog_posts_count','desc');
    }

    public function scopeMostBlogPostsLastMonth(Builder $query)
    {
        return $query->withCount(['blogPosts'=> function (Builder $query){
            $query->whereBetween(static::CREATED_AT, [now()->subMonths(1), now()]);
        }])
        ->having('blog_posts_count','>=', 2)
        ->orderBy('blog_posts_count','desc');

        //has is giving wrong value
        // return $query->withCount(['blogPosts' => function(Builder $query) {
        //     $query->where('created_at', '>=', now()->subDays(10));
        // }])
        // ->has('blogPosts', '>=', 2)
        // ->orderBy('blog_posts_count', 'desc');
        //->orderBy('name', 'asc');
    }

    public function scopeThatHasCommentedOnPost(Builder $query, BlogPost $post)
        {
            return $query->whereHas('comments',function ($query) use ($post){
                return $query->where('commentable_id','=',$post->id)
                        ->where('commentable_type', '=', BlogPost::class);
            });
        }
}
