<?php

namespace App\Tests\Utils;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomKernelTestCase extends KernelTestCase
{
    protected KernelBrowser $client;
    protected $fixtures;
    protected Registry $doctrine;
    protected ValidatorInterface $validator;


    public function setUp(): void
    {
        $this->fixtures = static::getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
        $this->fixtures = $this->fixtures->load(['tests/data_fixtures.yaml']);

        $this->doctrine = static::$kernel->getContainer()->get('doctrine');

        $this->validator = static::getContainer()->get(ValidatorInterface::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $purger = new ORMPurger($this->doctrine->getManager());
        $purger->purge();
    }

    protected function getValidationErrors($object, int $numberOfExpectedErrors): ConstraintViolationList
    {
        $errors = $this->validator->validate($object);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }
}
