<?php

namespace Tests\Unit\Divante\EnrichmentProgressBundle\Controller;

use Pimcore\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class DefaultControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testSomething()
    {
        $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
