<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    private function createUser($username, $plainPassword, $roles = ['ROLE_USER'],$firstname,$lasname,$age,$StudentNo,$Email,$Telephone,$Add1,$Add2,$City,$County,$Created):User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setRoles($roles);
        // password - and encoding
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);
        $user->setFirstName($firstname);
        $user->setLastName($lasname);
        $user->setAge($age);
        $user->setStudentNumber($StudentNo);
        $user->setEmail($Email);
        $user->setTelephone($Telephone);
        $user->setAddressline($Add1);
        $user->setAddresslineone($Add2);
        $user->setCity($City);
        $user->setCounty($County);
        $user->setDatecreated($Created);
        return $user;
    }

    public function load(ObjectManager $manager)
    {
        // create objects

        $User = $this->createUser('dylan', 'seery', ['ROLE_ADMIN'] , 'Dylan', 'Seery','20','B00098463','Dylanseery98outlook.ie','0852368369','123 Slick road' ,'Finglas South' ,'Finglas', 'Dublin' , new \DateTime());
        $manager->persist($User);

        $manager->flush();
    }
}
