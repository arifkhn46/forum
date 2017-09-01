<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Get user feeds.
     * @param User $user
     * @param int $take
     * @return static
     */
    public static function feed(User $user, $take = 50)
    {
        return  $user->activity()
            ->latest()
            ->with('subject')
            ->take($take)
            ->get()
            ->groupBy(function($activity){
                return $activity->created_at->format('Y-m-d');
            });
    }
}
