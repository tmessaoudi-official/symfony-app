<?php

namespace App\Doctrine\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SuperAdminFixture extends Fixture implements FixtureGroupInterface
{
    protected UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('super-admin@local.io');
        $user->setUsername('super-admin');
        $user->setFullName('Super Admin');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, 'developer'));

        $manager->persist($user);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['super_admin_users', 'admin_users', 'users'];
    }
}
