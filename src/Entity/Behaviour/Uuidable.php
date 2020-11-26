<?php

namespace App\Entity\Behaviour;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

trait Uuidable
{
    /**
     * @var UuidInterface | null
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @Groups({"uuid"})
     */
    protected ?UuidInterface $id;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }
}
