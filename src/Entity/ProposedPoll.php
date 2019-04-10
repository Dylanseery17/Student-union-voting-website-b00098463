<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProposedPollRepository")
 */
class ProposedPoll
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
    private $Name;

    /**
     * @ORM\Column(type="json")
     */
    private $Image = [];

    /**
     * @ORM\Column(type="json")
     */
    private $Options = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="integer")
     */
    private $Support;

    /**
     * @ORM\Column(type="json")
     */
    private $Users = [];


    public function __construct()
    {
        $this->Users = new ArrayCollection();
        $this->Support = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getImage(): ?array
    {
        return $this->Image;
    }

    public function setImage(array $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->Options;
    }

    public function setOptions(array $Options): self
    {
        $this->Options = $Options;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getSupport(): ?int
    {
        return $this->Support;
    }

    public function setSupport(int $Support): self
    {
        $this->Support = $Support;

        return $this;
    }

    public function getUsers(): ?array
    {
        return $this->Users;
    }

    public function setUsers(array $Users): self
    {
        $this->Users = $Users;

        return $this;
    }

}
