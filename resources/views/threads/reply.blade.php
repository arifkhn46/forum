<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex">
                <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }} </a>said {{ $thread->created_at->diffForHumans() }}
            </h5>
            @if(auth()->check())
                <div>
                    <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                        {{ csrf_field() }}
                        <button class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : ''}}>
                            {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
    <div class="panel-body">
        <div class="body">{{ $reply->body }}</div>
    </div>
</div>