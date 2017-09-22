<?php

namespace App\Inspections;

use Exception;

class InvalidKeyWords
{
    /**
     * Invalid Key words list.
     *
     * @var array
     */
    protected $keywords = [
        'yahoo customer support',
    ];

    /**
     * Detect if the string is spam.
     *
     * @param $body
     * @throws Exception
     */
    public function detect($body)
    {
        foreach ($this->keywords  as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('Your reply contains spam.');
            }
        }
    }
}