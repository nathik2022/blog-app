<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    
    public function testNoBlogPostsFoundWhenNothingInDatabase()
    {
        $response = $this->get('/posts');
        $response->assertSeeText('No post found');
    }

    public function testSee1BlogPostWhenThereIs1WithNoComments(){

        //Arrange
        $post = $this->createDummyBlogPost();

        //Act
        $response = $this->get('/posts');

        //Assert
        $response->assertSeeText('New title');
        $response->assertSeeText('No comments yet');


        $this->assertDatabaseHas('blog_posts',[
            'title' => 'New title'
        ]);
    }

    public function testSee1BlogPostWhenThereIs1WithComments(){
        
        //Arrange
        $user = $this->user();
        $post = $this->createDummyBlogPost();
        Comment::factory()->count(4)->create([
            'commentable_id' => $post->id,
            'commentable_type' => 'App\Models\BlogPost',
            'user_id' => $user->id,
        ]);

        //Act
        $response = $this->get('/posts');

        //Assert
        $response->assertSeeText('4 comments');
    }

    public function testStoreValidBlogPost(){

        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters'
        ];
        $this->actingAs($this->user())
            ->post('/posts',$params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'),'The blog post is created');    
    }

    public function testStoreFailBlogPost(){
        
        $params = [
            'title' => 'x',
            'content'=> 'x'
        ];

        $this->actingAs($this->user())
            ->post('/posts',$params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $message = session('errors')->getMessages();    
        $this->assertEquals($message['title'][0],'The title must be at least 5 characters.' );   
        $this->assertEquals($message['content'][0],'The content must be at least 10 characters.' );
        
        //dd($message->getMessages());    
    }

    public function testUpdateValidBlogPost(){

        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);

        //$this->assertDatabaseHas('blog_posts', $post->toArray());
        $this->assertDatabaseHas('blog_posts',[
            'title' => 'New title',
            'content'=> 'Content of the blog post'
        ]);

        $params = [
            'title' => 'A New Named Update Valid title',
            'content' => 'Content has changed'
        ];
        $this->actingAs($user)
            ->put("/posts/{$post->id}",$params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'),'Blog post was updated!');
        $this->assertDatabaseMissing('blog_posts', $post->toArray());
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'A New Named Update Valid title']);    
    }

    public function testDeleteBlogPost(){
        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);

        //dd($post->getAttributes());

        //$this->assertDatabaseHas('blog_posts', $post->toArray());
        $this->assertDatabaseHas('blog_posts',[
            'title' => 'New title',
            'content'=> 'Content of the blog post'
        ]);

        $this->actingAs($user)
            ->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'),'Blog post was deleted!');
       // $this->assertDatabaseMissing('blog_posts', $post->toArray());
        $this->assertSoftDeleted('blog_posts', $post->toArray());
        /*$this->assertSoftDeleted('blog_posts', [
            'title' => 'New Title',
            'content' => 'Content of the blog post'
        ]);*/    
    }

    private function createDummyBlogPost($userId = null):BlogPost
    {
        // $post = new BlogPost();
        // $post->title = 'New title';
        // $post->content = 'Content of the blog post';
        // $post->save();

        $post = BlogPost::factory()->newTitle()->create(
            [
                'user_id' => $userId ?? $this->user()->id,
                //'created_at' => date('Y-m-d H:i:s',strtotime(Carbon::now()->timestamp)),
                //'updated_at' => Carbon::now()->timestamp,
            ]
        );
 
        return $post;
    }

}
