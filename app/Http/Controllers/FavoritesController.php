<?php

namespace App\Http\Controllers;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Reply $reply)
    {
        $reply->favorite();
        return back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unFavorite();
    }
}
