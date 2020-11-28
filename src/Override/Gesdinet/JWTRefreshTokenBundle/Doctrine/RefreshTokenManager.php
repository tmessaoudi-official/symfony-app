<?php

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

    protected RefreshTokenRepository|ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager, string $gesdinetJWTRefreshTokenEntityClass)
    {
        $this->objectManager = $entityManager;
        $this->repository = $entityManager->getRepository($gesdinetJWTRefreshTokenEntityClass);
        $this->class = $entityManager->getClassMetadata($gesdinetJWTRefreshTokenEntityClass)->getName();
    }

    /**
     * @param string $refreshToken
     */
    public function get($refreshToken): RefreshTokenInterface|null
    {
        return $this->repository->findOneBy(array('refreshToken' => $refreshToken));
    }

    /**
     * @param string $username
     */
    public function getLastFromUsername($username): RefreshTokenInterface|null
    {
        return $this->repository->findOneBy(array('username' => $username), array('valid' => 'DESC'));
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
     * Returns the RefreshToken fully qualified class name.
     *
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
