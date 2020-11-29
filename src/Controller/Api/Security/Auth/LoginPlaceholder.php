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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth/login', name: 'api_security_auth_login', methods: ['GET', 'POST'])]
class LoginPlaceholder
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'code' => Response::HTTP_FORBIDDEN,
            'message' => 'Direct access unauthorized.',
        ], Response::HTTP_FORBIDDEN);
    }
}
