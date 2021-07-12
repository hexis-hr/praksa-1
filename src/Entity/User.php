<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $UserID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $LastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $FirstName;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $Mail;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $Password;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    public function getName(){
        return $this->Name;
    }

    public function setName(string $Name){
        $this->Name = $Name;
        return $this;

    }

    public function getUserID(): ?int
    {
        return $this->UserID;
    }

    public function setUserID(int $UserID): self
    {
        $this->UserID = $UserID;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->Mail;
    }

    public function setMail(string $Mail): self
    {
        $this->Mail = $Mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        array_push($roles, 'ROLE_USER');

        return array_unique($roles);
    }

    public function setRole(?string $role): self
    {
        if (!in_array($role, $this->roles))
            array_push($this->roles, $role);

        return $this;
    }

    public function revokeRole(string $role): self
    {
        unset($this->roles[$role]);

        return $this;
    }

    public function getSalt()
    {
        return NULL; /* this function returns NULL because the hashing algorithm
        used does not require it */
    }

    public function getUsername() : ?string
    {
        return $this->getMail();
    }

    /* this function is unnecessary because all sensitive information is
    encrypted, but is defined because this class implements UserInterface */
    public function eraseCredentials()
    {

    }
}

