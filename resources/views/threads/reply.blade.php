<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }} </a>said {{ $thread->created_at->diffForHumans() }}
                </h5>
                @if(auth()->check())
                    <div>
                        <favorite :reply="{{ $reply }}"></favorite>
                    </div>
                @endif
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" v-on:click="update">Update</button>
                <button class="btn btn-xs btn-links" v-on:click="editing = false">Cancel</button>
            </div>
            <div v-else>
                <div class="body" v-text="body"></div>
            </div>
        </div>

        @can ('update', $reply)
            <div class="panel-footer level">
                <button class="btn btn-xs mr-1" v-on:click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs mr-1" v-on:click="destroy">Delete</button>
                <!-- <form action="/replies/{{ $reply->id }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-danger btn-xs">Delete</button>
                </form> -->
            </div>
        @endcan
    </div>
</reply>
