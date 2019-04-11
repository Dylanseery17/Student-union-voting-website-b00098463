<?php

namespace App\DataFixtures;

use App\Entity\Poll;
use App\Entity\ProposedPoll;
use App\Entity\User;
use App\Entity\Vote;
use Faker\Factory;
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

    private function createUser($username, $plainPassword, $Profile, $firstname,$lasname,$age,$StudentNo,$Email,$Telephone,$Add1,$Add2,$City,$County,$Created,$roles = ['ROLE_USER']):User
    {
        $user = new User();
        $user->setUsername($username);
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
        $user->setImage($Profile);
        $user->setRoles($roles);
        return $user;
    }

    private function createPoll($name, $image, $options, $description,$endate,$startdate):Poll
    {
        $poll = new Poll();
        $poll->setName($name);
        $poll->setImage($image);
        $poll->setOptions($options);
        $poll->setDescription($description);
        $poll->setEnddate($endate);
        $poll->setStartdate($startdate);
        return $poll;
    }

    private function createProposed($name, $image, $options, $description,$support):ProposedPoll
    {
        $poll = new ProposedPoll();
        $poll->setName($name);
        $poll->setImage($image);
        $poll->setOptions($options);
        $poll->setDescription($description);
        $poll->setSupport($support);
        return $poll;
    }

    private function createVote($user, $poll, $choice):Vote
    {
        $vote = new Vote();
        $vote->setVoter($user);
        $vote->setPoll($poll);
        $vote->setChoice($choice);
        $vote->setTime( new \DateTime());
        return $vote;
    }


    public function load(ObjectManager $manager)
    {
        // create objects

        //POLL FIXTURES
        $poll1 = $this->createPoll('Student Election',["https://timedotcom.files.wordpress.com/2019/03/donald-trump-robert-mueller.jpg?quality=85&w=1012&h=569&crop=1","https://amp.businessinsider.com/images/5c81615b2628982a921addc7-750-375.jpg"],["Donald Trump" ,"Conor McGregor"],'Vote for student election',new \DateTime('2019-04-21 T15:03:01.012345Z'),new \DateTime());
        $poll2 = $this->createPoll('Bus Stop Shelters',["https://cloud.lovindublin.com/images/_featuredImage/Dublin-Bus_180511_094105.jpg?mtime=20180511094104%20880w"],["Stop 1819" ,"Stop 1820" ,"Stop 3946"],'Which dublin bus stop needs a bus shelter', new \DateTime('2019-06-21 T15:03:01.012345Z'),new \DateTime());
        $poll3 = $this->createPoll('Canteen Food',["https://images.unsplash.com/photo-1497888329096-51c27beff665?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=751&q=80"],["Healthy Food ","Fast Food"],'Junk Food vs Healthy Food. Junk food and healthy food are antagonistic and keep clashing with each other over nutrients and calories. ... Junk food which is rich in calories, fat, sugar and salt is much yummier and easier to prepare than healthy food.', new \DateTime('2018-04-15 T15:03:01.012345Z'),new \DateTime('2018-04-15 T15:03:01.012345Z'));
        $poll4 = $this->createPoll('Computer Science 4th year modules',["/Uploads/4cae03ad4e32a359d9e18941caaed7ff.jpg"],["Network Security ","Enterprise and Cloud Computing ","Ubiquitous Computing ","Game Development ","Applied Human Language Technology ,Data Analytics"],'Three electives must be selected Computer Science 4th year electives', new \DateTime('2019-04-26 T15:03:01.012345Z'),new \DateTime());

        $manager->persist($poll1);
        $manager->persist($poll2);
        $manager->persist($poll3);
        $manager->persist($poll4);
        //END OF POLL

        //POLL FIXTURES
        $Propoll1 = $this->createProposed('Cups in Libary',["/Uploads/e3f4787ce0319d0d3c1d646394916b6c.jpg","/Uploads/75022f4ec889d030417db4b52a4b729f.jpg"],["Plastic" , "Glass"],'Please support this poll :D',10);
        $Propoll2 = $this->createProposed('Libary Open',["/Uploads/07a3bba1a6655260a9355ba4327a444c.jpg"],["Open" , "Closed"],'Should libary be open over easter??', 50);

        $manager->persist($Propoll1);
        $manager->persist($Propoll2);
        //END OF POLL

        //USER FIXTURES
        $User = $this->createUser('dylan', 'seery', 'https://avatars0.githubusercontent.com/u/32263323?s=460&v=4','Dylan', 'Seery','20','B00098463','Dylanseery98outlook.ie','0852368369','123 Slick road' ,'Finglas South' ,'Finglas', 'Dublin' , new \DateTime(),['ROLE_ADMIN'] );
        $Useruser = $this->createUser('user', 'user', '' ,'user', 'user','user','user','user','user','user' ,'user' ,'user', 'user' , new \DateTime());
        $admin = $this->createUser('admin', 'admin', '' ,'admin', 'admin','admin','admin','admin','admin','admin' ,'admin' ,'admin', 'admin' , new \DateTime(),['ROLE_ADMIN']);


        $manager->persist($User);
        $manager->persist($Useruser);
        $manager->persist($admin);

        $faker = Factory::create();
        $numStudents = 30;
        for ($i=0; $i < $numStudents; $i++) {
            $Name = $faker->name;
            $Userfaker = $this->createUser($Name,$faker->password,$faker->imageUrl($width = 640, $height = 480),$faker->firstName,$faker->lastName,'20','B000000',''.$Name.'@gmail.com',$faker->e164PhoneNumber,$faker->streetName,$faker->city,$faker->state,$faker->state,new \DateTime());
            $manager->persist($Userfaker);
        }
        //END OF USERS

        $vote1 = $this->createVote($User,$poll2,"Stop 1820");
        $vote2 = $this->createVote($Useruser,$poll2,"Stop 1820");
        $vote3 = $this->createVote($admin,$poll2,"Stop 1819");

        $manager->persist($vote1);
        $manager->persist($vote2);
        $manager->persist($vote3);

        $manager->flush();
    }
}
