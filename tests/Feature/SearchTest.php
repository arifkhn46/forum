<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_search_threads()
    {
        config(['scout.driver' => 'algolia']);
        $search = 'foobar';
        create('App\Thread', [], 2);
        $desiredThreads = create('App\Thread', ['body' => "A thread with the {$search} term"], 2);
        do {
            $result = $this->getJson("/threads/search?q={$search}")->json();    
        }while(empty($result));
        $this->assertCount(2, $result['data']);
        $desiredThreads->unsearchable();
    }   
}
