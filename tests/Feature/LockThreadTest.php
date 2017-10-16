<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LockThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function non_administrator_may_not_lock_threads()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->post(route('locked-threads.store', $thread))->assertStatus(403);
        $this->assertFalse((bool) $thread->fresh()->locked);
    }

    /** @test */
    function administrator_can_lock_threads()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->post(route('locked-threads.store', $thread))->assertStatus(200);
        $this->assertTrue((bool) $thread->fresh()->locked);
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_no_replies()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $thread->lock();

        $this->post($thread->path() . '/replies', ['body' => 'Foobar', 'user_id' => auth()->id()])
            ->assertStatus(422);
    }
}
