<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
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
     * @Route("/Admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function admin()
    {
        return $this->render('default/admin.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

}
