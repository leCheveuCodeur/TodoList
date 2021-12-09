<?php

namespace App\Tests\Func\Controller;

use App\Tests\Utils\CustomWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends CustomWebTestCase
{
    public function taskUri()
    {
        return [
            ['/tasks'],
            ['/tasks/create'],
            ['/tasks/1/edit'],
            ['/tasks/1/toggle'],
            ['/tasks/1/delete']
        ];
    }

    /**
     * @dataProvider taskUri
     */
    public function testAccessTaskUriWithNoAuthenticated($uri)
    {
        $this->client->request('GET', $uri);
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testAccessTaskUriWithAutenticated()
    {
        $user = $this->fixtures['user_1'];
        $this->client->loginUser($user);
        $this->client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
    }

    // test the possibility to modify a Task that does not own us - POSSIBLE
    public function testEditingTaskWithoutRights()
    {
        $user = $this->fixtures['user_2'];
        $this->client->loginUser($user);
        $this->client->request('GET', '/tasks/1/edit');
        $this->client->submitForm('Modifier', [
            'task[title]' => 'super titre à rallonnnnnnnnggggggggggge',
            'task[content]' => 'contenu super court'
        ]);
        $this->client->followRedirect();
        $this->assertSelectorExists('div[class*="alert alert-success"]');
        $this->assertSelectorTextContains('a[href*="/tasks/1/edit"]', 'super titre à rallonnnnnnnnggggggggggge');
    }

    // test the possibility to delete a Task that does not own us - NOT POSSIBLE
    public function testDeletingTaskWithoutRights()
    {
        $user = $this->fixtures['user_2'];
        $this->client->loginUser($user);
        $this->client->request('GET', '/tasks/1/delete');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    // test the possibility to delete a Task that belongs to us - POSSIBLE
    public function testDeletingTaskWithRights()
    {
        $user = $this->fixtures['user_1'];
        $this->client->loginUser($user);
        $this->client->request('GET', '/tasks/1/delete');
        $this->client->followRedirect();
        $this->assertSelectorExists('div[class*="alert alert-success"]');
        $this->assertSelectorNotExists('a[href*="/tasks/1/edit"]');
    }

    // test creation of a Task - POSSIBLE
    public function testCreateNewTask()
    {
        $user = $this->fixtures['user_1'];
        $this->client->loginUser($user);
        $this->client->request('GET', '/tasks/create');
        $this->client->submitForm('Ajouter', [
            'task[title]' => 'super titre à rallonnnnnnnnggggggggggge',
            'task[content]' => 'contenu super court'
        ]);
        $this->client->followRedirect();
        $this->assertSelectorExists('div[class*="alert alert-success"]');
        $this->assertSelectorTextContains('a[href*="/tasks/2/edit"]', 'super titre à rallonnnnnnnnggggggggggge');
    }


    // test the toggle on a task with the author and with a third party user
    public function setUserTest()
    {
        return [
            ['user_1'], //author of task_1
            ['user_2'] //other user
        ];
    }

    /**
     * @dataProvider setUserTest
     */
    public function testToggleOnTask($user_test)
    {
        $user = $this->fixtures[$user_test];
        $this->client->loginUser($user);
        $this->client->request('GET', '/tasks');
        $this->client->submitForm('task_1');
        $this->client->followRedirect();
        $this->assertSelectorExists('div[class*="alert alert-success"]');
        $this->assertSelectorExists('div[class*="alert alert-success"]');
        $this->assertSelectorTextContains('button[id="task_1"]', 'Marquer non terminée');
    }
}
