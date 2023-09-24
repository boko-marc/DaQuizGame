<?php

namespace App\Tests\Unit\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameTest extends WebTestCase
{


    public function testItWork(): void
    {
        $this->assertEquals(expected: 42, actual: 42);
    }
}
