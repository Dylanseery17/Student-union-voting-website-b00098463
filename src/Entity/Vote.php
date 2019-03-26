<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 */
class Vote
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="VoteID")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Voter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Poll", inversedBy="Poll_id")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Poll;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Choice;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Time;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVoter(): ?User
    {
        return $this->Voter;
    }

    public function setVoter(?User $Voter): self
    {
        $this->Voter = $Voter;

        return $this;
    }

    public function getPoll(): ?Poll
    {
        return $this->Poll;
    }

    public function setPoll(?Poll $Poll): self
    {
        $this->Poll = $Poll;

        return $this;
    }

    public function getChoice(): ?string
    {
        return $this->Choice;
    }

    public function setChoice(string $Choice): self
    {
        $this->Choice = $Choice;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->Time;
    }

    public function setTime(\DateTimeInterface $Time): self
    {
        $this->Time = $Time;

        return $this;
    }

}
