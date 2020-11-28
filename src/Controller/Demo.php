<?php

namespace App\Controller;

use App\Service\DemoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Demo extends AbstractController
{
    public function __invoke(DemoService $demoService)
    {
        // TODO: Implement __invoke() method.
    }
}
