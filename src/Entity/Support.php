<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SupportRepository")
 */
class Support
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="User")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProposedPoll", inversedBy="ProposedPoll")
     */
    private $Proposed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getProposed(): ?ProposedPoll
    {
        return $this->Proposed;
    }

    public function setProposed(?ProposedPoll $Proposed): self
    {
        $this->Proposed = $Proposed;

        return $this;
    }
}
