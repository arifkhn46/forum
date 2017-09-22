<?php

namespace App\Inspections;

class Spam
{
    /**
     * Inspections to be perform.
     * @var array
     */
    protected $inspections = array(
        InvalidKeyWords::class,
        KeyHeldDown::class
    );

    public function detect($body)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }
        return false;
    }
}