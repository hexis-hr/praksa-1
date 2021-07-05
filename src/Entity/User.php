<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UsersRepository::class)
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $PrivilegeID; // will likely be deprecated soon

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
        if ($this->PrivilegeID == 1)
            return ['ROLE_ADMIN'];
        else
            return ['ROLE_USER'];
    }

    // TODO: Implement function to set roles
    /*public function setRoles(?array $role): self
    {
        if ($role == 'ROLE_ADMIN')
            $this->PrivilegeID = 1;
        else if ($role == 'ROLE_USER')
            $this->PrivilegeID = 0;

        return $this;
    }*/

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

