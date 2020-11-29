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

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Behaviour\Arrays\Ops;
use App\Entity\Behaviour\Uuidable;
use App\Repository\DummyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass=DummyRepository::class)
 */
class Dummy
{
    use Uuidable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $name;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected ?array $tags = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="dummies")
     */
    protected ?User $user;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = array_unique($tags);

        return $this;
    }

    public function clearTags(): self
    {
        $this->tags = [];

        return $this;
    }

    public function addTag(?string $tag): self
    {
        $this->tags = Ops::addItem($this->tags, $tag);

        return $this;
    }

    public function removeTag(?string $tag): self
    {
        $this->tags = Ops::removeItem($this->tags, $tag);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
