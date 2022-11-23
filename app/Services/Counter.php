<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Counter
{
    public function increment(string $key, array $tags = null): int
    {
        $sessionId = session()->getId();
        $counterKey = "{$key}-counter";
        $usersKey = "{$key}-users";

        $cacheName = "{$key}-users";
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

        return $counter;
    }

}