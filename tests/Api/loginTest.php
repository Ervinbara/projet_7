<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class loginTest extends WebTestCase
{
    public function testValid(){
        $client = static::createClient();
        $client->request('GET', '/login_check');
    }
}