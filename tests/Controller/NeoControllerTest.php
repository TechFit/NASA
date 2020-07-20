<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NeoControllerTest extends WebTestCase
{
    public function testShowHazardous()
    {
        $client = static::createClient();

        $client->request('GET', '/neo/hazardous');

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/neo/hazardous/20/0');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowFastest()
    {
        $client = static::createClient();

        $client->request('GET', '/neo/fastest');

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/neo/fastest?hazardous=true');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowBestMonth()
    {
        $client = static::createClient();

        $client->request('GET', '/neo/best-month');

        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowException()
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $this->expectException(NotFoundHttpException::class);

        $client->request('GET', '/neo/hazardous/1/2/3');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        $client->request('GET', '/neo/best-month1');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        $client->request('GET', '/');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}