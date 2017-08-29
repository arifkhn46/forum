@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>
                        {{ $profileUser->name }}
                    </h1>
                    <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
                </div>
                @foreach ($threads as $thread)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
	                	<span class="flex">
	                		<a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                            <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
	                	</span>
                                <span>{{ $thread->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="body">{{ $thread->body }}</div>
                        </div>
                    </div>
                @endforeach
                {{ $threads->links() }}
            </div>
        </div>
    </div>
@endsection