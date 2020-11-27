<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Route('/dummy', name: 'api_dummy', methods: ['GET', 'POST'])]
class Dummy
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            "code" => Response::HTTP_OK,
            "message" => "It works without api platform.",
            "data" => [
                "first" => true,
                "second" => false,
            ]
        ], Response::HTTP_OK);
    }
}
