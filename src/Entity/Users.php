<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users
{


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $PrivelegeID;
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

    public function getPrivelegeID(): ?int
    {
        return $this->PrivelegeID;
    }

    public function setPrivelegeID(?int $PrivelegeID): self
    {
        $this->PrivelegeID = $PrivelegeID;

        return $this;
    }
}

