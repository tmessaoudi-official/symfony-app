<?php

namespace App\Override\Gesdinet\JWTRefreshTokenBundle\Security\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use Gesdinet\JWTRefreshTokenBundle\Security\Provider\RefreshTokenProvider as OriginalRefreshTokenProvider;

class RefreshTokenProvider extends OriginalRefreshTokenProvider
{
    protected OriginalRefreshTokenProvider $decorated;

    protected EntityManagerInterface $entityManager;

    public function __construct(RefreshTokenManagerInterface $refreshTokenManager, OriginalRefreshTokenProvider $decorated, EntityManagerInterface $entityManager)
    {
        parent::__construct($refreshTokenManager);
        $this->decorated = $decorated;
        $this->entityManager = $entityManager;
    }

    public function setCustomUserProvider(UserProviderInterface $customUserProvider)
    {
        $this->decorated->setCustomUserProvider($customUserProvider);
    }

    public function getUsernameForRefreshToken($token)
    {
        return $this->decorated->getUsernameForRefreshToken($token);
    }

    public function loadUserByUsername($username)
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $username]);
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->decorated->refreshUser($user);
    }

    public function supportsClass($class)
    {
        return $this->decorated->supportsClass($class);
    }
}
