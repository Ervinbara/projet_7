<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

class Usertest extends TestCase
{
    public function test()
    {
        $ervin = true;
        if ($ervin == true) {
            $this->assertEquals($ervin, true);
        }
    }
}