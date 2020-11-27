<?php

namespace App\Entity\Behaviour;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Uuidable
{
    /**
     * @var \Ramsey\Uuid\UuidInterface | null
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
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
