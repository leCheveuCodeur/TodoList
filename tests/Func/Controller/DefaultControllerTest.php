<?php

namespace App\Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private $fixtures;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->fixtures = static::getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
        $this->fixtures = $this->fixtures->load(['tests/data_fixtures.yaml']);
    }

    public function featuresForAuthenticatedUser()
    {
        return [
            ['a[href*=\/logout]'],
            ['a[href="/tasks/create"]'],
            ['a[href*=\/tasks]']
        ];
    }

    /**
     * @dataProvider featuresForAuthenticatedUser
     */
    public function testAccessHomepageWithAuthenticated($selector)
    {
        $user = $this->fixtures['user_1'];
        $this->client->loginUser($user);
        $this->client->request('GET', '/');
        $this->assertSelectorExists($selector);
    }

    public function testAccessHomepageWithNoAutenticated()
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }
}
