<?php

namespace App\Http\Controllers;

use App\Events\CommentPosted as EventsCommentPosted;
use App\Http\Requests\StoreComment;
//use App\Jobs\NotifyUsersPostWasCommented;
//use App\Jobs\ThrottledMail;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Comment as CommentResource;

//use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->only(['store','create','edit','update','destroy']);
    }

    public function index(BlogPost $post)
    {
        return CommentResource::collection($post->comments()->with('user')->get());
        //return $post->comments()->with('user')->get();
    }


    public function store(BlogPost $post, StoreComment $request)
    {
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

        // Mail::to($post->user)->send(
        //     // new CommentPosted($comment)
        //     new CommentPostedMarkdown($comment)
        // );
        //$when = now()->addMinutes(1);
        
        // Mail::to($post->user)->queue(
        //     new CommentPostedMarkdown($comment)
        // );
        
        event(new EventsCommentPosted( $comment));

        // Mail::to($post->user)->later(
        //     $when,
        //     new CommentPostedMarkdown($comment)
        // );

        //$request->session()->flash('status','Comment was created');

        return redirect()->back()->withStatus('Comment was created');
    }
}
