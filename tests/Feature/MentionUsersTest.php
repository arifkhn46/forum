<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mention_users_in_a_reply_are_notified()
    {
        $john = create('App\User', ['name' => 'JohnDoe']);
        $this->signIn($john);
        $jane = create('App\User', ['name' => 'JaneDoe']);
        $thread = create('App\Thread');
        $reply = make('App\Reply', [ 'body' => '@JaneDoe Look at this.']);
        $this->json('post', $thread->path() . '/replies', $reply->toArray());
        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    public function it_can_fetch_all_mention_users_starting_with_the_given_characters()
    {
        create('App\User', ['name' => 'JohnDoe']);
        create('App\User', ['name' => 'JohnDoe2']);
        create('App\User', ['name' => 'JaneDoe']);
        $result = $this->json('GET', '/api/users', ['name' => 'john']);
        $this->assertCount(2, $result->json());
    }
}
