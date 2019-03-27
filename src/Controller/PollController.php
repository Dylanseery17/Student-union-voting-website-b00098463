<?php

namespace App\Controller;

use App\Entity\Poll;
use App\Form\PollType;
use App\Entity\Vote;
use App\Entity\User;
use App\Form\VoteType;
use App\Repository\PollRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/poll")
 */
class PollController extends AbstractController
{
    /**
     * @Route("/", name="poll_index", methods={"GET"})
     */
    public function index(PollRepository $pollRepository): Response
    {
        return $this->render('poll/index.html.twig', [
            'polls' => $pollRepository->findAll(),
        ]);
    }

    /**
     * @Route("/vote/{id}", name="poll_vote", methods={"GET","POST"})
     */
    public function vote(Request $request ,Poll $poll ,PollRepository $pollRepository): Response
    {
        $vote = new Vote();
        $user = new User();
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);
        $row = $pollRepository->findByID($poll);

        $manager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $usr = $_POST['user'];
            $usr = (int) $usr;
            $pol = $_POST['poll'];
            $pol = (int) $pol;
            $vpoll = $manager->getRepository('App:Poll')->find($pol);
            $vuser = $manager->getRepository('App:User')->find($usr);


            $Cho = $_POST['Choice'];
            $vote->setChoice($Cho);
            $vote->setVoter($vuser);
            $vote->setPoll($vpoll);
            $vote->setTime(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();

            return $this->redirectToRoute('vote_index');
        }
        return $this->render('vote/pollvote.html.twig', [
            'vote' => $vote,
            'form' => $form->createView(),
            'poll' => $poll,
            'poll_choice' => $row,
        ]);

    }

    /**
     * @Route("/new", name="poll_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $poll = new Poll();
        $form = $this->createForm(PollType::class, $poll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($poll);
            $entityManager->flush();

            return $this->redirectToRoute('poll_index');
        }

        return $this->render('poll/new.html.twig', [
            'poll' => $poll,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="poll_show", methods={"GET"})
     */
    public function show(Poll $poll): Response
    {
        return $this->render('poll/show.html.twig', [
            'poll' => $poll,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="poll_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Poll $poll): Response
    {
        $form = $this->createForm(PollType::class, $poll);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('poll_index', [
                'id' => $poll->getId(),
            ]);
        }

        return $this->render('poll/edit.html.twig', [
            'poll' => $poll,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="poll_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Poll $poll): Response
    {
        if ($this->isCsrfTokenValid('delete'.$poll->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($poll);
            $entityManager->flush();
        }

        return $this->redirectToRoute('poll_index');
    }
}
