<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Get the replies associated with a threads.
     */
    public function index($channelId, Thread $thread)
    {
        if (request()->wantsJson()) {
            return $thread->replies()->paginate(20);
        }
    }

    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), ['body' => 'required']);

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);

         if (request()->expectsJson()) {
            return $reply->load('owner');
        }
        return back();
    }

    /**
     * Destroy a reply.
     *
     * @param \App\Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();
        if (request()->expectsJson()) {
            return response(['status' => 'Reply Deleted!']);
        }
        return back();
    }


    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update(request(['body']));
    }
}
