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

namespace App\Override\Gesdinet\JWTRefreshTokenBundle\Doctrine;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManager as RefreshTokenManagerModel;

class RefreshTokenManager extends RefreshTokenManagerModel
{
    protected EntityManagerInterface $objectManager;

    protected string $class;

    protected ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager, string $gesdinetJWTRefreshTokenEntityClass)
    {
        $this->objectManager = $entityManager;
        $this->repository = $entityManager->getRepository($gesdinetJWTRefreshTokenEntityClass);
        $this->class = $entityManager->getClassMetadata($gesdinetJWTRefreshTokenEntityClass)->getName();
    }

    /**
     * @param string $refreshToken
     */
    public function get($refreshToken): ?RefreshTokenInterface
    {
        return $this->repository->findOneBy(['refreshToken' => $refreshToken]);
    }

    /**
     * @param string $username
     */
    public function getLastFromUsername($username): ?RefreshTokenInterface
    {
        return $this->repository->findOneBy(['username' => $username], ['valid' => 'DESC']);
    }

    public function save(RefreshTokenInterface $refreshToken, bool $andFlush = true): void
    {
        $this->objectManager->persist($refreshToken);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    public function delete(RefreshTokenInterface $refreshToken, bool $andFlush = true): void
    {
        $this->objectManager->remove($refreshToken);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @return RefreshTokenInterface[]
     */
    public function revokeAllInvalid(DateTime $datetime = null, bool $andFlush = true): array
    {
        $invalidTokens = $this->repository->findInvalid($datetime);

        foreach ($invalidTokens as $invalidToken) {
            $this->objectManager->remove($invalidToken);
        }

        if ($andFlush) {
            $this->objectManager->flush();
        }

        return $invalidTokens;
    }

    /**
     * @return RefreshTokenInterface[]
     */
    public function deleteBy(?array $extraCriteria = null): array
    {
        $userRefreshTokens = $this->repository->findBy($extraCriteria);

        foreach ($userRefreshTokens as $refreshToken) {
            $this->objectManager->remove($refreshToken);
        }

        $this->objectManager->flush();

        return $userRefreshTokens;
    }

    /**
     * Returns the RefreshToken fully qualified class name.
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
