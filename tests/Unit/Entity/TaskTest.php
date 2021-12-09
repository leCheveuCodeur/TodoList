<?php

namespace App\Tests;

use DateTime;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Tests\Utils\CustomKernelTestCase;

class TaskTest extends CustomKernelTestCase
{
    private Task $task;

    private const TITLE_NOT_BLANK_MESSAGE = 'Vous devez saisir un titre.';
    private const CONTENT_NOT_BLANK_MESSAGE = 'Vous devez saisir un contenu.';


    public function newTaskEntity()
    {
        if (empty($this->task)) {
            $this->task = new Task;
        }
        return $this->task->setAuthor($this->fixtures['user_2'])
            ->setTitle('super tritre')
            ->setContent('super contenu')
            ->setCreatedAt(new DateTime());
    }

    public function testTaskEntityIsValid()
    {
        return $this->getValidationErrors($this->newTaskEntity(), 0);
    }

    public function testTaskEntityIsInvalidBecauseNoTitleEntered()
    {
        $this->newTaskEntity()->setTitle('');

        $errors = $this->getValidationErrors($this->task, 1);
        return $this->assertEquals(self::TITLE_NOT_BLANK_MESSAGE, $errors[0]->getMessage());
    }

    public function testTaskEntityIsInvalidBecauseNoContentEntered()
    {
        $this->newTaskEntity()->setContent('');

        $errors = $this->getValidationErrors($this->task, 1);
        return $this->assertEquals(self::CONTENT_NOT_BLANK_MESSAGE, $errors[0]->getMessage());
    }

    public function testGetCreatedDateOfTask()
    {
        /** @var TaskRepository */
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $this->task = $taskRepository->findOneBy(['id' => '1']);

        $this->assertNotEmpty($this->task->getCreatedAt());
    }
}
