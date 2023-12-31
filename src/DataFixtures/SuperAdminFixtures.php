<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SuperAdminFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $superAdmin = $this->createSuperAdmin();

        $manager->persist($superAdmin);

        $manager->flush();
    }

    private function createSuperAdmin() : User
    {
        $user = new User();

        $passwordHashed = $this->hasher->hashPassword($user, "azerty1234A*");

        $user->setFirstName('Nestor');
        $user->setLastName('Gavi');
        $user->setEmail('friclumiere@gmail.com');
        $user->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER']);
        // $user->setIsVerified(true);
        $user->setPassword($passwordHashed);
        // $user->setVerifiedAt(new DateTimeImmutable('now'));

        return $user;
    }
}
