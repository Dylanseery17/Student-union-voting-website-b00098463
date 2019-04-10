<?php

namespace App\Controller;

use App\Entity\ProposedPoll;
use App\Form\ProposedPollType;
use App\Form\ProposedSupport;
use App\Repository\ProposedPollRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/proposed/poll")
 */
class ProposedPollController extends AbstractController
{
    /**
     * @Route("/", name="proposed_poll_index", methods={"GET"})
     */
    public function index(ProposedPollRepository $proposedPollRepository): Response
    {
        return $this->render('proposed_poll/index.html.twig', [
            'proposed_polls' => $proposedPollRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="proposed_poll_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $proposedPoll = new ProposedPoll();
        $form = $this->createForm(ProposedPollType::class, $proposedPoll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploads_directory = $this->getParameter('uploads_directory');
            $file = $request->files->get('proposed_poll')['Upload_Image'];
            $img = [];
            foreach($file as $files){
                $filename = md5(uniqid()) . '.' . 'jpg';
                if($files == null){
                }else{
                    $files->move(
                        $uploads_directory,
                        $filename
                    );
                    array_push($img, '/Uploads/'.$filename);
                    var_dump($img);
                }
                $proposedPoll->setImage($img);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proposedPoll);
            $entityManager->flush();

            return $this->redirectToRoute('proposed_poll_index');
        }

        return $this->render('proposed_poll/new.html.twig', [
            'proposed_poll' => $proposedPoll,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proposed_poll_show" )
     */
    public function show(Request $request, ProposedPoll $proposedPoll): Response
    {
        $form = $this->createForm(ProposedSupport::class, $proposedPoll);
        $form->handleRequest($request);

        $manager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {

            $proposed = $manager->getRepository('App:ProposedPoll')->find($proposedPoll);
            $proposedPoll = $proposed->setSupport($proposed->getSupport() + 1 );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proposedPoll);
            $entityManager->flush();

            return $this->redirectToRoute('proposed_poll_show', [
                'id' => $proposedPoll->getId(),
            ]);
        }

        return $this->render('proposed_poll/show.html.twig', [
            'proposed_poll' => $proposedPoll,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="proposed_poll_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProposedPoll $proposedPoll): Response
    {
        $form = $this->createForm(ProposedPollType::class, $proposedPoll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploads_directory = $this->getParameter('uploads_directory');
            $file = $request->files->get('proposed_poll')['Upload_Image'];
            $img = [];
            foreach($file as $files){
                $filename = md5(uniqid()) . '.' . 'jpg';
                if($files == null){
                }else{
                    $files->move(
                        $uploads_directory,
                        $filename
                    );
                    array_push($img, '/Uploads/'.$filename);
                    var_dump($img);
                }
                $proposedPoll->setImage($img);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proposed_poll_index', [
                'id' => $proposedPoll->getId(),
            ]);
        }

        return $this->render('proposed_poll/edit.html.twig', [
            'proposed_poll' => $proposedPoll,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="proposed_poll_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProposedPoll $proposedPoll): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proposedPoll->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($proposedPoll);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_proposed_index');
    }
}
