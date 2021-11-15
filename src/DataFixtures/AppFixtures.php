<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    private $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        //Admin User
        $admin = new User;
        $admin->setUsername($_ENV['ADMIN_PSEUDO'])
            ->setEmail($_ENV['ADMIN_EMAIL'])
            ->setPassword($this->passwordHasher->hashPassword($admin, $_ENV['ADMIN_PASSWORD']))
            ->setRoles([User::ROLE_ADMIN]);

        $manager->persist($admin);
        $manager->flush();

        //Fakes Datas
        if ($_ENV['APP_ENV'] === 'dev') {

            for ($u = 0; $u < 3; $u++) {
                $user = new User;
                $user->setUsername('test' . ($u + 1))
                    ->setEmail('test' . ($u + 1) . '@test.com')
                    ->setPassword($this->passwordHasher->hashPassword($user, 'test'))
                    ->setRoles([User::ROLE_USER]);

                $manager->persist($user);
            }
            $manager->flush();

            for ($t = 0; $t < 20; $t++) {
                $task = new Task;
                $task->setAuthor($this->userRepository->find(\mt_rand(1, 4)))
                    ->setTitle($faker->sentence())
                    ->setContent($faker->text())
                    ->setCreatedAt(new DateTime())
                    ->toggle((bool) \random_int(0, 1));

                $manager->persist($task);
            }

            for ($t = 0; $t < 3; $t++) {
                $task = new Task;
                $task->setTitle($faker->sentence())
                    ->setContent($faker->text())
                    ->setCreatedAt(new DateTime())
                    ->toggle((bool) \random_int(0, 1));

                $manager->persist($task);
            }
            $manager->flush();
        }
    }
}
