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

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dummy', name: 'api_dummy', methods: ['GET', 'POST'])]
class Dummy
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'message' => 'It works without api platform.',
            'data' => [
                'first' => true,
                'second' => false,
            ],
        ], Response::HTTP_OK);
    }
}
