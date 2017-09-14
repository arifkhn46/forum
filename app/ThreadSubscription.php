<?php

namespace App;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class ThreadSubscription extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notify($reply) {
        dd($this->user);
        $this->user->notify(new ThreadWasUpdated($this, $reply));
    }
}
