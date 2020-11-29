<?php

namespace App\Override\Gesdinet\JWTRefreshTokenBundle\Entity;

use App\Entity\Behaviour\Uuidable;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\AbstractRefreshToken;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Override\Gesdinet\JWTRefreshTokenBundle\Repository\RefreshTokenRepository;

/**
 *
 * @UniqueEntity("refreshToken")
 * @ORM\Entity(repositoryClass=RefreshTokenRepository::class)
 */
class RefreshToken extends AbstractRefreshToken
{
    use Uuidable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $ip;

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }
}
