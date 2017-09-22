<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filter\ThreadFilter;
use App\Inspections\Spam;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Channel $channel
     * @param \App\Filter\ThreadFilter $filters
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilter $filters)
    {
        $threads = $this->getThreads($channel, $filters);
        if (request()->wantsJson()) {
            return $threads;
        }
        return view('threads.index', compact('threads'));
    }

    public  function show($chanelId, Thread $thread)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }
        return view('threads.show', compact('thread'));
    }

    public function store(Request $request, Spam $spam)
    {
        $this->validate($request, [
            'title' => 'required',
            'body'  => 'required',
            'channel_id' => 'required|exists:channels,id',
        ]);
        $spam->detect(request('body'));
        $spam->detect(request('title'));
        $thread = Thread::create([
            'user_id' => auth()->id(),
            'title' => request('title'),
            'channel_id' => request('channel_id'),
            'body' => request('body'),
        ]);
        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');
    }

    public function create()
    {
        return view('threads.create');
    }

    /**
     * Fetch all relevant threads.
     *
     * @param Channel       $channel
     * @param \App\Filter\ThreadFilter $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilter $filters)
    {
        $threads = Thread::latest()->filter($filters);
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        return $threads->get();
    }

    /**
     * Delete a thread.
     *
     * @param integer $channel
     * @param \App\Thread $thread
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        $thread->delete();
        if (request()->wantsJson()) {
            return response([], 204);
        }
        return redirect('/threads');
    }
}
