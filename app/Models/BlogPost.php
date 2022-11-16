<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

//use Laravel\Ui\Presets\Bootstrap;

class BlogPost extends Model
{
    protected $fillable =['title','content','user_id'];
    use HasFactory;
    use SoftDeletes,Taggable;

    public function comments(){

        return $this->morphMany('App\Models\Comment','commentable')->lastest();

    }

    public function user(){
        
        return $this->belongsTo('App\Models\User');

    }

    public function image()
    {
        return $this->morphOne('App\Models\Image','imageable');
    }

    public function scopeLastest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT,'desc');
    }

    public function scopeMostCommented(Builder $query)
    {
        //comments_count
        return $query->withCount('comments')->orderBy('comments_count','desc');
    }

    public function scopeLatestWithRelations(Builder $query)
    {
        return $query->lastest()
        ->withCount('comments')
        ->with('user','tags');
    }

    public static function boot()
    {
        static::addGlobalScope(new DeletedAdminScope);

        parent::boot();

        //static::addGlobalScope(new LatestScope);

        static::deleting(function(BlogPost $blogPost){
            $blogPost->comments()->delete();
            Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        });

        static::updating(function (BlogPost $blogPost){    
            Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        });

        static::restoring(function(BlogPost $blogPost){
            $blogPost->comments()->restore();
        });
    }
}
