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

namespace App\Manager\Api\Security\Auth;

use App\Entity\User;
use App\Override\Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenManager;
use Symfony\Component\Security\Core\User\UserInterface;

class LogoutManager
{
    protected RefreshTokenManager $refreshTokenManager;

    public function __construct(RefreshTokenManager $refreshTokenManager)
    {
        $this->refreshTokenManager = $refreshTokenManager;
    }

    public function __invoke(UserInterface | User $user): void
    {
        //$this->refreshTokenManager->revokeAllInvalid();
        //$this->refreshTokenManager->delete($this->refreshTokenManager->getLastFromUsername($user->getEmail()));
        $this->refreshTokenManager->deleteByUser($user);
    }
}