<?php

namespace Tests\Feature;

use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_user_can_create_a_new_forum_thread()
    {
        $user = factory('App\User')->states('confirmed')->create();
        $this->signIn($user);
        $thread = make('App\Thread');
        $response = $this->post(route('threads'), $thread->toArray());
        $this->get($response->headers->get('Location'))
                ->assertSee($thread->title)
                ->assertSee($thread->body);
    }

    /** @test */
    public function guests_can_not_create_threads()
    {
        $this->withExceptionHandling();
        $this->get('/threads/create')
            ->assertRedirect(route('login'));
        $this->post(route('threads'), [])
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_thread_requires_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_valid_channel_id()
    {
        factory('App\Channel', 2)->create();
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');
        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_requirs_a_unique_slug()
    {
        $thread = create('App\Thread', ['title' => 'Foo Title', 'slug' => 'foo-title']);
        $this->assertEquals($thread->fresh()->slug, 'foo-title');
        $this->publishThread($thread->toArray());
        $this->assertDatabaseHas('threads', ['slug' => 'foo-title-2']);
    }

    public function publishThread($overrides = [])
    {
        $user = factory('App\User')->states('confirmed')->create();
        $this->withExceptionHandling()->signIn($user);
        $thread = make('App\Thread', $overrides);
        return $this->post(route('threads'), $thread->toArray());
    }

    /** @test */
    public function unauthorized_users_may_not_delete_thread()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        $response = $this->delete($thread->path());
        $response->assertRedirect(route('login'));
        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }
    
    /** @test */
    public function authorized_thread_can_be_deleted()
    {
        $user = create('App\User');
        $this->signIn($user);
        $thread = create('App\Thread', ['user_id' => $user->id]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);
        $this->assertDatabaseMissing('threads', ['id' => $thread->id ]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id ]);
        $this->assertEquals(0, Activity::count());
    }

    /** @test */
    public function new_users_must_confirm_thier_email_address_before_creating_threads()
    {
        $this->signIn();
        $thread = make('App\Thread');
        $this->post(route('threads'), $thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash');
    
    }
}
