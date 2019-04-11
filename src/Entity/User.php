<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    public function __toString()
    {
        if(is_null($this->username)) {
            return 'NULL';
        }
        return $this->username;
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vote", mappedBy="Voter", orphanRemoval=true)
     */
    private $VoteID;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $Image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Support", mappedBy="User")
     */
    private $Poll;


    public function __construct()
    {
        $this->VoteID = new ArrayCollection();
        $this->Poll = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        if(null != $roles){}

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return Collection|Vote[]
     */
    public function getVoteID(): Collection
    {
        return $this->VoteID;
    }

    public function addVoteID(Vote $voteID): self
    {
        if (!$this->VoteID->contains($voteID)) {
            $this->VoteID[] = $voteID;
            $voteID->setVoter($this);
        }

        return $this;
    }

    public function removeVoteID(Vote $voteID): self
    {
        if ($this->VoteID->contains($voteID)) {
            $this->VoteID->removeElement($voteID);
            // set the owning side to null (unless already changed)
            if ($voteID->getVoter() === $this) {
                $voteID->setVoter(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    /**
     * @return Collection|Support[]
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(Support $user): self
    {
        if (!$this->User->contains($user)) {
            $this->User[] = $user;
            $user->setUser($this);
        }

        return $this;
    }

    public function removePoll(Support $user): self
    {
        if ($this->User->contains($user)) {
            $this->User->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getUser() === $this) {
                $user->setUser(null);
            }
        }

        return $this;
    }

}
