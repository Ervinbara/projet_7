<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class loginTest extends WebTestCase
{
    public $token;

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
        $this->token = $content['token'];
        printf($content['token']);
    }

    public function testGetProducts(){
        $client = static::createClient();
        $client->jsonRequest('GET', '/api/produits');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetDetailsProducts(){
        $client = static::createClient();
        $client->jsonRequest('GET', '/api/produits/3');
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetUsers(){
        printf($this->token);
        $client = static::createClient();
        $client->jsonRequest('GET', '/api/users/dodo');
        $this->assertJson($client->getResponse()->getContent());
    }
}
