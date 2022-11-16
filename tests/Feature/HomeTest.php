<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHomePageIsWorkingCorrectly()
    {
        $response = $this->get('/');

        $response->assertSeeText('Welcome to Laravel!');
        $response->assertSeeText('This is the content of the main page!');
    }

    public function testContactPageIsWorkingCorrectly()
    {
        $response = $this->get('/contact');
        
        $response->assertSeeText('Contact Page');
        $response->assertSeeText('This is the content of the contact page!');
    }
}
