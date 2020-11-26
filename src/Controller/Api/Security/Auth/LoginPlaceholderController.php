<?php

namespace App\Controller\Api\Security\Auth;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Route('/auth/login', name: 'api_security_auth_login', methods: ['GET', 'POST'])]
class LoginPlaceholderController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            "code" => Response::HTTP_FORBIDDEN,
            "message" => "Direct access unauthorized."
        ], Response::HTTP_FORBIDDEN);
    }
}
