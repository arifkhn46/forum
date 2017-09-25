<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\Http\Forms\CreatePostForm;
use Illuminate\Support\Facades\Gate;

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

    /**
     * Stores new reply.
     *
     * @param $channelId
     * @param Thread $thread
     * @param \App\Http\Controllers\CreatePostForm $form
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread, CreatePostForm $form)
    {
        if (Gate::denies('create', new Reply)) {
            return response('You are posting too frequently, please take a break.', 422);
        }
        try {
            $this->validate(request(), ['body' => 'required|spamfree']);
            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            return response('Sorry your reply could not be saved this time.', 422);
        }

        return $reply->load('owner');
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
        try {
            $this->authorize('update', $reply);
            $this->validate(request(), ['body' => 'required|spamfree']);
            $reply->update(request(['body']));
        } catch(\Exception $e){
            return response('Sorry your reply could not be saved this time.', 422);
        }
    }
}
