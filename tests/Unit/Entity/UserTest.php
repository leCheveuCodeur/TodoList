<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Utils\CustomKernelTestCase;

class UserTest extends CustomKernelTestCase
{
    private const USERNAME_NOT_BLANK_MESSAGE = 'Vous devez saisir un nom d\'utilisateur.';
    private const EMAIL_NOT_BLANK_MESSAGE = 'Vous devez saisir une adresse email.';
    private const EMAIL_CONSTRAINT_MESSAGE = 'Le format de l\'adresse n\'est pas correcte.';
    private const PASSWORD_CONSTRAINT_MESSAGE = 'Vous devez saisir un mot de passe.';
    private const EMAIL_INVALID_VALUE = 'testtest.com';

    private User $user;

    public function newUserEntity()
    {
        if (empty($this->user)) {
            $this->user = new User;
        }
        return $this->user->setUsername('test')
            ->setEmail('test@test.com')
            ->setPassword('test1234');
    }
    public function testUserEntityIsValid()
    {
        return $this->getValidationErrors($this->newUserEntity(), 0);
    }

    public function testUserEntityIsInvalidBecauseNoUsernameEntered()
    {
        $this->newUserEntity()->setUsername('');

        $errors = $this->getValidationErrors($this->user, 1);
        return $this->assertEquals(self::USERNAME_NOT_BLANK_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseNoEmailEntered()
    {
        $this->newUserEntity()->setEmail('');

        $errors = $this->getValidationErrors($this->user, 1);
        return $this->assertEquals(self::EMAIL_NOT_BLANK_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseEmailInvalid()
    {
        $this->newUserEntity()->setEmail(self::EMAIL_INVALID_VALUE);

        $errors = $this->getValidationErrors($this->user, 1);
        return $this->assertEquals(self::EMAIL_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseNoPasswordEntered()
    {
        $this->newUserEntity()->setPassword('');

        $errors = $this->getValidationErrors($this->user, 1);
        return $this->assertEquals(self::PASSWORD_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function getUserEntity()
    {
        /** @var UserRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->user = $userRepository->findOneBy(['username' => 'user_1']);
    }
    public function testGetUserTask()
    {
        $this->getUserEntity();
        $this->assertCount(1, $this->user->getTasks());
    }

    public function testRemoveUserTask()
    {
        $this->getUserEntity();
        $this->user->removeTask($this->user->getTasks()[0]);

        $this->assertCount(0, $this->user->getTasks());
    }
}
