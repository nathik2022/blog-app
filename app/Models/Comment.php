<?php

namespace App\Models;


use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Comment extends Model
{
    use HasFactory;
    use SoftDeletes, Taggable;

    protected $fillable = ['user_id','content'];

    protected $hidden = ['deleted_at','commentable_type','commentable_id','user_id'];

    //blog_post_id
    public function commentable()
    {

    // return $this->belongsTo('App\Models\BlogPost','post_id','blog_post_id');
        //return $this->belongsTo('App\Models\BlogPost');
        return $this->morphTo();

    }

    public function user(){
        
        return $this->belongsTo('App\Models\User');

    }

    public function scopeLastest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT,'desc');
    }

}
