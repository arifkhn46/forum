<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        $this->signIn();
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());
        $this->get($thread->path())
                ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function guests_can_not_create_threads()
    {
        $this->withExceptionHandling();
        $this->get('/threads/create')
            ->assertRedirect('/login');
        $this->post('/threads', [])
            ->assertRedirect('/login');
    }
}
