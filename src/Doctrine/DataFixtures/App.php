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

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class App extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
