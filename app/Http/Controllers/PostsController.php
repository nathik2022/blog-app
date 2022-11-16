<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Filesystem\FilesystemManager;

//use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create','store','update','edit','destroy','restore']);
    }
   
    // private  $posts = [
    //     1 => [
    //         'title' => 'Intro to Laravel',
    //         'content' => 'This is a short intro to Laravel',
    //         'is_new' => true,
    //         'has_comments' => true

    //     ],
    //     2 => [
    //         'title' => 'Intro to PHP',
    //         'content' => 'This is a short intro to PHP',
    //         'is_new' => false
    //     ],
    //     3 => [
    //         'title' => 'Intro to Golang',
    //         'content' => 'This is a short intro to Golang',
    //         'is_new' => false
    //     ]
    // ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //DB::connection()->enableQueryLog();
        // DB::enableQueryLog();
        
        // //$posts  = BlogPost::all();
        // $posts = BlogPost::with('comments')->get();

        // foreach($posts as $post) {
        //     foreach($post->comments as $comment){
        //         echo $comment->content;
        //     }
        // }

        // dd(DB::getQueryLog());

        //Below data now coming from ActivityComposer Using AppServiceProvider

        // $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-most-commented',now()->addSeconds(20),function (){
        //     return BlogPost::mostCommented()->take(5)->get();
        // });

        // $mostActive = Cache::tags(['blog-post'])->remember('user-most-active',now()->addSeconds(20),function (){
        //     return User::mostBlogPosts()->take(5)->get();
        // });

        // $mostActiveLastMonth= Cache::tags(['blog-post'])->remember('most-active-last-month',now()->addSeconds(20),function (){
        //     return User::mostBlogPostsLastMonth()->take(5)->get();
        // });
        
        //comments_count
        
        //->orderBy('created_at','desc')

        return view(
            'posts.index',
            //['posts'=>BlogPost::withCount('comments')->get()]);
            [
                'posts'=>BlogPost::latestWithRelations()->get(),
                // 'mostCommented' => $mostCommented, data now coming from ActivityComposer Using AppServiceProvider
                // 'mostActive' => $mostActive,
                // 'mostActiveLastMonth' => $mostActiveLastMonth,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$this->authorize('posts.create');
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        //dd($request);
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        // $post = new BlogPost();
        // $post->title = $validated['title'];
        // $post->content = $validated['content'];
        // $post->save();
        $post = BlogPost::create($validated);
        
        if($request->hasFile('thumbnail')){
            $path = $request->file('thumbnail')->store('thumbnails');
            $post->image()->save(
                Image::make(['path'=>$path])
            );
            // dump($file);
            // dump($file->getClientOriginalExtension());
            // dump($file->getClientMimeType());
            
            //  dump($file->store('thumbnails'));
            //  dump(Storage::disk('public')->put('thumbnail',$file));
            
            // $name1 = $file->storeAs('thumbnails',$post->id.'.'.$file->guessExtension());
            // //$name2 = Storage::putFileAs('thumbnails',$file,$post->id.'.'.$file->guessExtension());
            // $name2 = $request->file('thumbnail')->storeAs('thumbnails',$post->id.'.'.$file->guessExtension(),'local');
            // dump(Storage::url($name1));
            // //dump(Storage::disk('local')->url($name2));
            
        }

        $request->session()->flash('status','The blog post is created');

        return redirect()->route('posts.show',['post'=>$post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //abort_if(!isset($this->posts[$id]),404);
        //'post'=> BlogPost::with('comments')->findOrFail($id)

        // return view('posts.show',[
        //     'post'=> BlogPost::with(['comments' => function($query){
        //         return $query->lastest();
        //     }])->findOrFail($id)
        // ]);

        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}",60, function()use($id){
            return BlogPost::with('comments','user','tags','comments.user')
                ->findOrFail($id);
        });

        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";

        // $users = Cache::tags(['blog-post'])->get($usersKey,[]);
        // $usersUpdate = [];
        // $difference = 0;
        // $now = now();

        // foreach ($users as $session => $lastVisit){
        //     if($now->diffInMinutes($lastVisit) >= 1){
        //         $difference--;
        //     }else{
        //         $usersUpdate[$session] = $lastVisit;
        //     }
        // }

        // if(
        //     (!(array_key_exists($sessionId,$users)))
        //     ||
        //     ($now->diffInMinutes($users[$sessionId]) >=1)
        // ) {
        //     $difference++;
        // }

        // $usersUpdate[$sessionId] = $now;
        // Cache::tags(['blog-post'])->forever($usersKey,$usersUpdate);

        // if(!Cache::tags(['blog-post'])->has($counterKey)){
        //     Cache::tags(['blog-post'])->forever($counterKey,1);
        // }else{
        //     Cache::tags(['blog-post'])->increment($counterKey,$difference);
        // }

        // $counter = Cache::tags(['blog-post'])->get($counterKey);

        $cacheName = "blog-post-{$id}-users";
        $session_id = session()->getId();
        $now = now();
        
        $users = Cache::tags(['blog-post'])->get($cacheName, []);
        $users[$session_id] = $now;
        
        $updatedUsers = [];
        
        foreach($users as $session => $lastVisit){
            if($now->diffInMinutes($lastVisit) < 1 ){
                $updatedUsers[$session] = $lastVisit;
            }
        }
        
        Cache::tags(['blog-post'])->forever($cacheName, $updatedUsers);
        $counter = count($updatedUsers);

        return view('posts.show',[
            'post'=> $blogPost,
            'counter' => $counter,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findorFail($id);

        // if(Gate::denies('update-post',$post)){
        //     abort(403,"You can't edit this blog post!");
        // }
        
        //$this->authorize('posts.update',$post); this is old below is new
        //$this->authorize('update', $post);
        $this->authorize($post);

        return view('posts.edit',['post'=>$post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        // if(Gate::denies('update-post',$post)){
        //     abort(403,"You can't edit this blog post!");
        // }

        //$this->authorize('posts.update',$post); old below is the new
        //$this->authorize('update', $post);
        $this->authorize($post);
        
        $validated = $request->validated();

        $post->fill($validated);

        if($request->hasFile('thumbnail')){
            $path = $request->file('thumbnail')->store('thumbnails');

            if($post->image){
                Storage::delete($post->image->path);
                $post->image->path= $path;
                $post->image->save();
            }else{
                $post->image()->save(
                    Image::make(['path'=>$path])
                ); 
            }
        }

        $post->save();

        $request->session()->flash('status','Blog post was updated!');
        return redirect()->route('posts.show',['post'=>$post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd($id);
        $post = BlogPost::findOrFail($id);

        // if(Gate::denies('delete-post',$post)){
        //     abort(403,"You can't delete this blog post!");
        // }
        
        //$this->authorize('posts.delete',$post);old below is new 
        //$this->authorize('delete', $post);
        $this->authorize($post);
        $post->delete();

        session()->flash('status','Blog post was deleted!');
        return redirect()->route('posts.index');
    }

    public function restore($id)
    {
        $post = BlogPost::findOrFail($id);
        //$this->authorize($post);
        if(!(Auth::check() && Auth::user()->is_admin))
        {
            abort(403,"You can't restore this blog post!");
        }
        $post->restore();
        session()->flash('status', "Post was restored");
        return redirect()->route('posts.index');
    }
}
