<?php

namespace App\Tests\Func\Controller;

use App\Tests\Utils\CustomWebTestCase;

class DefaultControllerTest extends CustomWebTestCase
{
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
