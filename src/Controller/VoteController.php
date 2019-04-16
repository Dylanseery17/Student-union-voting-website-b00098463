<?php

namespace App\Controller;

use App\Entity\Poll;
use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\PollRepository;
use App\Repository\VoteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vote")
 */
class VoteController extends AbstractController
{
    /**
     * @Route("/", name="vote_index", methods={"GET"})
     */
    public function index(VoteRepository $voteRepository): Response
    {
        return $this->render('vote/index.html.twig', [
            'votes' => $voteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="vote_show", methods={"GET"})
     */
    public function show(Vote $vote): Response
    {
        return $this->render('vote/show.html.twig', [
            'vote' => $vote,
        ]);
    }

    /**
     * @Route("/{id}", name="vote_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Vote $vote): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vote->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_vote_index');
    }
}
