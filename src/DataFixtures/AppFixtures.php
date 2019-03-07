<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $User = new User();
        $User->setFirstName('Dylan');
        $User->setLastName('Seery');
        $User->setPassword('test123');
        $User->setUsername('Dylan98');
        $User->setAge('20');
        $User->setStudentNumber('B00098463');
        $User->setEmail('Dylanseery98@outlook.ie');
        $User->setTelephone('0852368912');
        $User->setAddressline('123 slick road');
        $User->setAddresslineone('finglas south');
        $User->setCity('Finglas');
        $User->setCounty('Dublin');
        $User->setDatecreated(new \DateTime());

        $manager->persist($User);

        $manager->flush();
    }
}
