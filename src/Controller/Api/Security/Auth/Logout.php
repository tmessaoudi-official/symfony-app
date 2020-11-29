<?php

namespace App\Controller\Api\Security\Auth;

use App\Service\Api\Security\Auth\LogoutService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

#[Route('/auth/logout', name: 'api_security_auth_logout', methods: ['POST'])]
class Logout
{
    public function __invoke(Security $security, LogoutService $logoutService): JsonResponse
    {
        $logoutService($security->getToken());
        return new JsonResponse([
            "code" => Response::HTTP_OK,
            "success" => true,
            "message" => "disconnected"
        ], Response::HTTP_OK);
    }
}
