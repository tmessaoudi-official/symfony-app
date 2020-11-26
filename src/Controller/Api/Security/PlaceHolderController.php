<?php

namespace App\Controller\Api\Security;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PlaceHolderController
{
    #[Route('/api/auth/login', name: 'api_auth_login')]
    public function login(): JsonResponse
    {
        return new JsonResponse([
            "code" => Response::HTTP_UNAUTHORIZED,
            "message" => "Direct access unauthorized."
        ], Response::HTTP_UNAUTHORIZED);
    }
}
