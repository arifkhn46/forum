<?php

namespace App;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (!auth()->check()) return;
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function($model){
                $model->recordActivity('created');
            });
        }
    }

    public static function getActivitiesToRecord()
    {
        return ['created'];
    }
    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }
    protected function getActivityType($event)
    {
        $type  = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}