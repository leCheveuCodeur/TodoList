<?php

namespace App\Tests\Utils;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected $fixtures;
    protected Registry $doctrine;

    public function setUp(): void
    {
        $this->client = static::createClient(['environment' => 'test', 'debug' => true]);

        $this->fixtures = static::getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
        $this->fixtures = $this->fixtures->load(['tests/data_fixtures.yaml']);

        $this->doctrine = static::$kernel->getContainer()->get('doctrine');
    }


    public function tearDown(): void
    {
        parent::tearDown();
        $purger = new ORMPurger($this->doctrine->getManager());
        $purger->purge();
    }
}
