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

namespace App\Entity\Behaviour;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Uuidable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @Groups({"uuid"})
     */
    protected ?\Ramsey\Uuid\UuidInterface $id;

    public function getId(): ?\Ramsey\Uuid\UuidInterface
    {
        return $this->id;
    }
}
