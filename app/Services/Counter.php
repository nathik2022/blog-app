<?php

namespace App\Services;

use App\Contracts\CounterContract;
use Illuminate\Support\Facades\Cache;
//use Illuminate\Contracts\Cache\Factory as Cache;
//use Illuminate\Cache\Repository as Cache;
use Illuminate\Contracts\Session\Session;

class Counter implements CounterContract
{
    public $timeout;
    private $cache;
    private $session;
    private $supportsTags;

    public function __construct(Cache $cache, Session $session,  int $timeout)
    {
        $this->cache = $cache;
        $this->session = $session;
        $this->timeout = $timeout;
        $this->supportsTags = method_exists($cache,'tags');
    }
    public function increment(string $key, array $tags = null): int
    {
        $sessionId = $this->session->getId();
        $counterKey = "{$key}-counter";
        $usersKey = "{$key}-users";
        $cache = $this->supportsTags && null !== $tags ? $this->cache->tags($tags) : $this->cache;

        $cacheName = "{$key}-users";
        $session_id = session()->getId();
        $now = now();
        
        $users = Cache::tags(['blog-post'])->get($cacheName, []);
        //$users = $cache->get($cacheName, []);
        //$users = $cache->get($cacheName, []);
        $users[$session_id] = $now;
        
        $updatedUsers = [];
        
        foreach($users as $session => $lastVisit){
            if($now->diffInMinutes($lastVisit) < $this->timeout ){
                $updatedUsers[$session] = $lastVisit;
            }
        }
        
        Cache::tags(['blog-post'])->forever($cacheName, $updatedUsers);
        //$cache->forever($cacheName, $updatedUsers);
       // $cache->get
        $counter = count($updatedUsers);

        return $counter;
    }

}