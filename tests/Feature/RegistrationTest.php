<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Auth\Events\Registered;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();
        
        $this->post(route('register'), [
            'name' => 'JohnDoe',
            'email' => 'johndoe@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

        Mail::assertSent(PleaseConfirmYourEmail::class);

    }

    /** @test */
    public function user_can_fully_confirm_their_email_addresses() 
    {
        Mail::fake();
         
        $this->post(route('register'), [
            'name' => 'JohnDoe',
            'email' => 'johndoe@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar',
        ]);

        $user = User::whereName('JohnDoe')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $response = $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads'));

        tap($user->fresh(), function($user) {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
    }

    /** @test */
    public function confirming_an_invalid_token() 
    {
        $this->get(route('register.confirm', ['token' => 'invalid']))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Invalid Token!');
    }
}