<?php

namespace App\Tests\Func\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\Utils\CustomWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends CustomWebTestCase
{
    public function userUri()
    {
        return [
            ['/users'],
            ['/users/1/edit'],
            ['/users/2/edit']
        ];
    }

    /**
     * @dataProvider userUri
     */
    public function testAccessUserUriWithNoAuthenticated($uri)
    {
        $this->client->request('GET', $uri);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testAccessUserUriWithAutenticatedButNoAdminRole()
    {
        $user = $this->fixtures['user_1'];
        $this->client->loginUser($user);
        $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->client->request('GET', '/users/2/edit');
        $this->assertResponseIsSuccessful();
        $this->client->request('GET', '/users/3/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @dataProvider userUri
     */
    public function testAccessUserUriWithAutenticatedAndAdminRole($uri)
    {
        $user = $this->fixtures['admin'];
        $this->client->loginUser($user);
        $this->client->request('GET', $uri);
        $this->assertResponseIsSuccessful();
    }

    public function testEditingUserWithRights()
    {
        $user = $this->fixtures['admin'];
        $this->client->loginUser($user);
        $this->client->request('GET', '/users/1/edit');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Modifier', [
            'user[password][first]' => 'test',
            'user[password][second]' => 'test',
        ]);
        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertSelectorExists('div[class*="alert alert-success"]');

        $this->client->request('GET', '/users/3/edit');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Modifier', [
            'user[username]' => 'random',
            'user[email]' => 'test@testsuccess.fr'
        ]);
        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertSelectorExists('div[class*="alert alert-success"]');
    }

    public function testEditingUserWithoutRights()
    {
        $user = $this->fixtures['user_1'];
        $this->client->loginUser($user);
        $this->client->request('GET', '/users/1/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN, "Vous n'êtes pas authorisé à accèder à cette page");
    }

    public function testCreateNewUser()
    {
        $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Ajouter', [
            'user[username]' => 'Boby',
            'user[email]' => 'boby@test.fr',
            'user[password][first]' => 'superpassword',
            'user[password][second]' => 'superpassword'
        ]);
        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}
