<?php

/*
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * @owner        : Mazars SA - 61 rue Henri Régnault, 92400 Courbevoie
 * @organization : EAZY By Mazars
 * @contact      : christophe.ballihaut@mazars.fr
 *
 * Mazars SA (c) 2016-present
 *
 */

namespace App\Entity\Behaviour;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait Uuidable.
 */
trait Uuidable
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @Groups({"uuid"})
     */
    protected $id;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }
}
