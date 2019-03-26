<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Poll;
use App\Repository\PollRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\Query;


class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="default_index")
     */
    public function index(PollRepository $pollRepository): Response
    {
        return $this->render('default/index.html.twig', [
            'polls' => $pollRepository->findAll(),
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/about", name="default_about")
     */
    public function about()
    {
        return $this->render('default/about.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/sitemap", name="default_sitemap")
     */
    public function sitemap()
    {
        return $this->render('default/sitemap.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/people", name="default_people")
     */
    public function people()
    {
        return $this->render('default/people.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function admin(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('default/admin.html.twig', [
            'users' =>  $users,
        ]);
    }

}
