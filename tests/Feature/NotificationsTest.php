<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->signIn();        

    }

    /** @test */
    public function a_notificaiton_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $thread = create('App\Thread')->subscribe();
        $this->assertCount(0, auth()->user()->fresh()->notifications);
        $thread->addReply(['user_id' => auth()->id(), 'body' => 'Some text here']);
        $this->assertCount(0, auth()->user()->fresh()->notifications);
        $anotherUser = create('App\User');
        $thread->addReply(['user_id' => $anotherUser->id, 'body' => 'Some text here']);
        $this->assertCount(1, auth()->user()->fresh()->notifications);

    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        create(DatabaseNotification::class);        
        $this->assertCount(1, auth()->user()->unreadNotifications);
        $notificationId = auth()->user()->unreadNotifications()->first()->id;
        $userId = auth()->user()->name;
        $this->delete("/profiles/{$userId}/notifications/{$notificationId}");
        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        create(DatabaseNotification::class);
        $this->assertCount(1, $this->getJson('/profiles/'. auth()->user()->name .'/notifications')->json());
    }
}
