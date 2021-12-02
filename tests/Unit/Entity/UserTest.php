<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTest extends KernelTestCase
{
    private const USERNAME_NOT_BLANK_MESSAGE = 'Vous devez saisir un nom d\'utilisateur.';
    private const EMAIL_NOT_BLANK_MESSAGE = 'Vous devez saisir une adresse email.';
    private const EMAIL_CONSTRAINT_MESSAGE = 'Le format de l\'adresse n\'est pas correcte.';
    private const PASSWORD_CONSTRAINT_MESSAGE = 'Vous devez saisir un mot de passe.';
    private const USERNAME_VALID_VALUE = 'test';
    private const PASSWORD_VALID_VALUE = 'test1234';
    private const EMAIL_VALID_VALUE = 'test@test.com';
    private const EMAIL_INVALID_VALUE = 'testtest.com';

    private ValidatorInterface $validator;
    private User $user;

    protected function setUp(): void
    {
        $this->validator = static::getContainer()->get(ValidatorInterface::class);
    }

    private function getValidationErrors(User $user, int $numberOfExpectedErrors): ConstraintViolationList
    {
        $errors = $this->validator->validate($user);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }

    public function getUserEntity()
    {
        if (empty($this->user)) {
            $this->user = new User;
        }
        return $this->user->setUsername(self::USERNAME_VALID_VALUE)
            ->setEmail(self::EMAIL_VALID_VALUE)
            ->setPassword(self::PASSWORD_VALID_VALUE);
    }
    public function testUserEntityIsValid()
    {
        return $this->getValidationErrors($this->getUserEntity(), 0);
    }

    public function testUserEntityIsInvalidBecauseNoUsernameEntered()
    {
        $this->getUserEntity()->setUsername('');

        $errors = $this->getValidationErrors($this->user, 1);
        return $this->assertEquals(self::USERNAME_NOT_BLANK_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseNoEmailEntered()
    {
        $this->getUserEntity()->setEmail('');

        $errors = $this->getValidationErrors($this->user, 1);
        return $this->assertEquals(self::EMAIL_NOT_BLANK_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseEmailInvalid()
    {
        $this->getUserEntity()->setEmail(self::EMAIL_INVALID_VALUE);

        $errors = $this->getValidationErrors($this->user, 1);
        return $this->assertEquals(self::EMAIL_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseNoPasswordEntered()
    {
        $this->getUserEntity()->setPassword('');

        $errors = $this->getValidationErrors($this->user, 1);
        return $this->assertEquals(self::PASSWORD_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }
}
