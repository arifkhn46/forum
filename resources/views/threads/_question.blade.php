{{-- Editing the question --}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="level">
            <input class="form-control"  type="text" v-model="form.title">
        </div>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <wysiwyg v-model="form.body" :value="form.body"></wysiwyg>
        </div>
    </div>
    <div class="panel-footer">
        <div class="level">
            <button class="btn btn-xs  level-item" v-on:click="editing = true" v-show=" ! editing">Edit</button>
            <button class="btn btn-primary btn-xs  level-item" v-on:click="update">Update</button>
            <button class="btn btn-xs  level-item" v-on:click="editing = false">Cancel</button>
            @can ('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-link" type="submit">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>

{{-- Viewing the question --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">
            <img src="{{ asset($thread->creator->avatar_path) }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-1">
            <span class="flex">
                <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a> posted:
                <span v-text="title"></span>
            </span>
        </div>
    </div>
    <div class="panel-body">
        <div class="body" v-html="body"></div>
    </div>
    <div class="panel-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-xs" v-on:click="editing = true">Edit</button>
    </div>
</div>