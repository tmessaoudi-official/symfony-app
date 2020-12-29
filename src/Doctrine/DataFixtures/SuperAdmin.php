<?php

/*
 * Personal project using Php 8/Symfony 5.2.x@dev.
 *
 * @author       : Takieddine Messaoudi <takieddine.messaoudi.official@gmail.com>
 * @organization : Smart Companion
 * @contact      : takieddine.messaoudi.official@gmail.com
 *
 */

declare(strict_types=1);

namespace App\Doctrine\DataFixtures;

use App\Entity\Dummy;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SuperAdmin extends Fixture implements FixtureGroupInterface
{
    protected UserPasswordEncoderInterface $userPasswordEncoder;
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('super-admin@local.io');
        $user->setUsername('super-admin');
        $user->setFullName('Super Admin');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->addRole('ROLE_ADMIN');
        $user->addRole('ROLE_USER');
        $user->addDummy(
            (new Dummy())
                ->setName('Super User Dummy')
                ->setUser($user)->setTags(['SU'])
                ->addTag('dummy')
                ->addTag('dummies')
                ->addTag('SUDO')
                ->removeTag('dummy')
        );
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, 'developer'));

        $manager->persist($user);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['super_admin_users', 'admin_users', 'users'];
    }
}
