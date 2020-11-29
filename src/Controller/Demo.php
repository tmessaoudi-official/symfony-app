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

namespace App\Controller;

use App\Service\DemoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Demo extends AbstractController
{
    public function __invoke(DemoService $demoService): void
    {
        // TODO: Implement __invoke() method.
    }
}
