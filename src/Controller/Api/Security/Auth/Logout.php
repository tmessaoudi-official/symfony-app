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

namespace App\Controller\Api\Security\Auth;

use App\Service\Api\Security\Auth\LogoutService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route(path="/auth/logout", name="security_auth_logout", methods={"POST"})
 */
class Logout
{
    public function __invoke(Security $security, LogoutService $logoutService): JsonResponse
    {
        $logoutService($security->getToken());

        return new JsonResponse([
            'code' => Response::HTTP_OK,
            'success' => true,
            'message' => 'disconnected',
        ], Response::HTTP_OK);
    }
}
