<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PollRepository")
 */
class Poll
{
    public function __toString()
    {
        if(is_null($this->Name)) {
        return 'NULL';
        }
        return $this->Name;
    }

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
    private $Image;

    /**
     * @ORM\Column(type="json")
     */
    private $Options = [];


    private $Poll;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="Poll", orphanRemoval=true)
     */
    private $Poll_id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $Description;


    /**
     * @ORM\Column(type="datetime")
     */
    private $startdate;


    /**
     * @ORM\Column(type="datetime")
     */
    private $enddate;


    public function __construct()
    {
        $this->Poll = new ArrayCollection();

        $this->startdate = new \DateTime();
        $this->Poll_id = new ArrayCollection();
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

        $Image = $this->Image;

        if(null != $Image){}

        return array_unique($Image);
    }

    public function setImage(array $Image): self
    {
        $this->Image = $Image;

        return $this;
    }


    public function getOptions(): ?array
    {
        $Options = $this->Options;

        if(null != $Options){}

        return array_unique($Options);

    }

    public function setOptions(array $Options): self
    {
        $this->Options = $Options;

        return $this;
    }

    /**
     * @return Collection|Vote[]
     */
    public function getPoll(): Collection
    {
        return $this->Poll;
    }

    public function addPoll(Vote $poll): self
    {
        if (!$this->Poll->contains($poll)) {
            $this->Poll[] = $poll;
            $poll->setPoll($this);
        }

        return $this;
    }

    public function removePoll(Vote $poll): self
    {
        if ($this->Poll->contains($poll)) {
            $this->Poll->removeElement($poll);
            // set the owning side to null (unless already changed)
            if ($poll->getPoll() === $this) {
                $poll->setPoll(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vote[]
     */
    public function getPollId(): Collection
    {
        return $this->Poll_id;
    }

    public function addPollId(Vote $pollId): self
    {
        if (!$this->Poll_id->contains($pollId)) {
            $this->Poll_id[] = $pollId;
            $pollId->setPoll($this);
        }

        return $this;
    }

    public function removePollId(Vote $pollId): self
    {
        if ($this->Poll_id->contains($pollId)) {
            $this->Poll_id->removeElement($pollId);
            // set the owning side to null (unless already changed)
            if ($pollId->getPoll() === $this) {
                $pollId->setPoll(null);
            }
        }

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

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(\DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

}
