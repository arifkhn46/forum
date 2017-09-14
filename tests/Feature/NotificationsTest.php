<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_notificaiton_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();
        $thread = create('App\Thread')->subscribe();
        $this->assertCount(0, auth()->user()->fresh()->notifications);
        $thread->addReply(['user_id' => auth()->id(), 'body' => 'Some text here']);
        $this->assertCount(0, auth()->user()->fresh()->notifications);
        $anotherUser = create('App\User');
        $thread->addReply(['user_id' => $anotherUser->id, 'body' => 'Some text here']);
        $this->assertCount(1, auth()->user()->fresh()->notifications);

    }
}
