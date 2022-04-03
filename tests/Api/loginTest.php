<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class loginTest extends WebTestCase
{
    public function testTokenIsValid()
    {
        $client = static::createClient();
        $client->jsonRequest('POST', '/api/login_check',[
            "username"=> "ervin@gmail.com",
            "password"=> "azerty"
        ]);

        $this->assertEquals( 200,$client->getResponse()->getStatusCode(), false);
        $this->assertJson($client->getResponse()->getContent());
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey("token", $content);
    }
}