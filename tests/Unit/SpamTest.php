<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{

    /** @test */
    public function it_validates_spam()
    {
        $spam = new Spam();
        $this->assertFalse($spam->detect('Innocent reply here'));
        $this->expectException(\Exception::class);
        $this->assertFalse($spam->detect('yahoo customer support'));
    }

    /** @test */
    public function it_checks_for_any_key_being_held_down()
    {
        $spam = new Spam();
        $this->expectException(\Exception::class);
        $spam->detect('Hello world aaaaaaaaaaaa');
    }
}
