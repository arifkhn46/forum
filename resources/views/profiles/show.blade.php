@extends('layouts.app')

@section('content')
	<div class="container">
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
	                		<a href="#">{{ $thread->creator->name }}</a> posted:
	                		{{ $thread->title }}
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
@endsection