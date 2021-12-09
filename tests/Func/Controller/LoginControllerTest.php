<?php

namespace App\Tests\Func\Controller;

use App\Tests\Utils\CustomWebTestCase;

class LoginControllerTest extends CustomWebTestCase
{
    public function testLoginWithValidCredentials()
    {
        $this->client->request('GET', '/login');
        $this->client->submitForm('Se connecter', [
            '_username' => 'admin',
            '_password' => 'test',
        ]);
        $this->client->followRedirect();
        $this->assertSelectorExists('a[href="/logout"]');
    }

    public function testLoginWithInvalidCredentials()
    {
        $this->client->request('GET', '/login');
        $this->client->submitForm('Se connecter', [
            '_username' => 'admin',
            '_password' => 'echec',
        ]);
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testLogout()
    {
        $user = $this->fixtures['admin'];
        $this->client->loginUser($user);
        $this->client->request('GET', '/');
        $this->client->clickLink('Se déconnecter');
        $this->client->followRedirect();
        $this->assertSelectorExists('a[href="/login"]');
    }
}
