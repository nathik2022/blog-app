<?php

namespace Tests;

use App\Models\BlogPost;
use App\Models\User;
//use Faker\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function user(){
        return User::factory()->create()->first();
    }

    protected function blogPost(){
        return BlogPost::factory()->create([
            'user_id' => $this->user()->id
        ]);
    }
}
