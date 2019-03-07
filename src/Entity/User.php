<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Age;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $StudentNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Addressline;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Addresslineone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $City;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $County;

    /**
     * @ORM\Column(type="date")
     */
    private $Datecreated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): self
    {
        $this->Username = $Username;

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

    public function getFirstname(): ?string
    {
        return $this->Firstname;

        return $this;
    }

    public function setFirstname(string $Firstname): self
    {
        $this->Firstname = $Firstname;

        return $this;
    }



    public function getLastname(): ?string
    {
        return $this->Lastname;
    }

    public function setLastname(string $Lastname): self
    {
        $this->Lastname = $Lastname;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->Age;
    }

    public function setAge(string $Age): self
    {
        $this->Age = $Age;

        return $this;
    }

    public function getStudentNumber(): ?string
    {
        return $this->StudentNumber;
    }

    public function setStudentNumber(string $StudentNumber): self
    {
        $this->StudentNumber = $StudentNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->Telephone;
    }

    public function setTelephone(string $Telephone): self
    {
        $this->Telephone = $Telephone;

        return $this;
    }

    public function getAddressline(): ?string
    {
        return $this->Addressline;
    }

    public function setAddressline(string $Addressline): self
    {
        $this->Addressline = $Addressline;

        return $this;
    }

    public function getAddresslineone(): ?string
    {
        return $this->Addresslineone;
    }

    public function setAddresslineone(string $Addresslineone): self
    {
        $this->Addresslineone = $Addresslineone;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->City;
    }

    public function setCity(string $City): self
    {
        $this->City = $City;

        return $this;
    }

    public function getCounty(): ?string
    {
        return $this->County;
    }

    public function setCounty(string $County): self
    {
        $this->County = $County;

        return $this;
    }

    public function getDatecreated(): ?\DateTimeInterface
    {
        return $this->Datecreated;
    }

    public function setDatecreated(\DateTimeInterface $Datecreated): self
    {
        $this->Datecreated = $Datecreated;

        return $this;
    }
}
