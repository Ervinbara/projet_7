<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class loginTest extends WebTestCase
{
    public $loginPayload = '{"email: "%s", "password: "%s"}';

    public $serverInformation = ['ACCEPT'=>'application/json', 'CONTENT_TYPE'=>'application/json'];

    public function testValid(){
        $client = static::createClient();
        $client->request('POST',
            '/api/login_check',
            $this->serverInformation,
            (array)$this->loginPayload, ['ervin@gmail.com', 'azerty']
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }
}