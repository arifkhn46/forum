<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Method to store a favorite against a reply.
     *
     * @param \App\Reply $reply
     */
    public function store(Reply $reply)
    {
        Favorite::create([
            'user_id' => auth()->id(),
            'favorited_id' => $reply->id,
            'favorited_type' => get_class($reply),
        ]);
    }
}
