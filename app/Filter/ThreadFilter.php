<?php

namespace App\Filter;

use App\User;

class ThreadFilter extends Filter
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return Builder
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query by popularity.
     *
     * @return Builder
     */
    protected function popular()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }

    /**
     * Filter the query by unanswered.
     *
     * @return Builder
     */
    protected function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}