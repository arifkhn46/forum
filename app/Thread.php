<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Filter\ThreadFilter;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });
        static::deleting(function($thread){
            $thread->replies->each->delete();
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        event(new ThreadReceivedNewReply($reply));
        return $reply;
    }
    
    /**
     * Channel relationship.
     *
     * @return void
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Apply all relevant thread filters.
     *
     * @param  Builder $query
     * @param \App\Filter\ThreadFilter $filters
     * @return Builder
     */
    public function scopeFilter($query, ThreadFilter $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Thread to user subscribed.
     * @param null $userId
     * @return $this
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
        ]);
        return $this;
    }

    /**
     * Thread to user subscribed.
     * @param null $userId
     */
    public function unsubscribed($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

    /**
     * Thread subscriptions.
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * Get if the user is subscribed to a thread.
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    public function hasUpdatesFor()
    {
        if (auth()->check()) {
            $key = auth()->user()->visitedThreadCacheKey($this);
            return $this->updated_at  > cache($key);
        }
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    protected function incrementSlug($slug) 
    {
        $max = static::whereTitle($this->title)->latest('id')->value('slug');
        
        if (is_numeric($max[-1])) {
            return preg_replace_callback('/(\d+)$/', function($matches) {
                return $matches[1]  + 1;
            }, $max);
        } 

        return "{$slug}-2";
    }
}
