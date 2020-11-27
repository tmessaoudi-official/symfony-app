<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Entity\Behaviour\Uuidable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Serializable;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 */
class User implements UserInterface, Serializable
{
    use Uuidable;

    /**
     * @var string | null
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private ?string $fullName;

    /**
     * @var string | null
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private ?string $username;

    /**
     * @var string | null
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email()
     */
    private ?string $email;

    /**
     * @var string | null
     *
     * @ORM\Column(type="string")
     */
    private ?string $password;

    /**
     * @var array | null
     *
     * @ORM\Column(type="json")
     */
    private ?array $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Dummy::class, mappedBy="user")
     */
    private Collection $dummies;

    public function __construct()
    {
        $this->dummies = new ArrayCollection();
    }

    public function setFullName(?string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(): ?array
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(?array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // We're using bcrypt in security.yaml to encode the password, so
        // the salt value is built-in and and you don't have to generate one
        // See https://en.wikipedia.org/wiki/Bcrypt

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|Dummy[]
     */
    public function getDummies(): Collection
    {
        return $this->dummies;
    }

    public function addDummy(Dummy $dummy): self
    {
        if (!$this->dummies->contains($dummy)) {
            $this->dummies[] = $dummy;
            $dummy->setUser($this);
        }

        return $this;
    }

    public function removeDummy(Dummy $dummy): self
    {
        if ($this->dummies->removeElement($dummy)) {
            // set the owning side to null (unless already changed)
            if ($dummy->getUser() === $this) {
                $dummy->setUser(null);
            }
        }

        return $this;
    }
}
