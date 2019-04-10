<?php
/**
 * Created by PhpStorm.
 * User: dylan
 * Date: 10/04/2019
 * Time: 15:42
 */

namespace App\Controller;

use App\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Poll;
use App\Entity\ProposedPoll;
use App\Form\ProposedPollType;
use App\Form\ProposedSupport;
use App\Repository\ProposedPollRepository;
use App\Repository\PollRepository;
use App\Controller\ProposedPollController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\UserType;
use Doctrine\ORM\Query;


/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin", methods={"GET"})
     */
    public function admin(UserRepository $userRepository , PollRepository $pollRepository  , ProposedPollRepository $proposedPollRepository , VoteRepository $voteRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/admin.html.twig', [
            'polls' => $pollRepository->findAll(),
            'users' =>  $users,
            'polls_count' => count($pollRepository->findAll()),
            'proposed_count' => count($proposedPollRepository->findAll()),
            'vote_count' => count($voteRepository->findAll()),
            'users_count' =>  count($users),
        ]);
    }

    /**
     * @Route("/user", name="user_index", methods={"GET"})
     */
    public function user(UserRepository $userRepository): Response
    {
        $dates = [];
        $day = date('Y-m-d', strtotime('-1 days'));
        array_push($dates, $day);
        $day = date('Y-m-d', strtotime('-2 days'));
        array_push($dates, $day);
        $day = date('Y-m-d', strtotime('-3 days'));
        array_push($dates, $day);
        $day = date('Y-m-d', strtotime('-4 days'));
        array_push($dates, $day);
        $day = date('Y-m-d', strtotime('-5 days'));
        array_push($dates, $day);
        $day = date('Y-m-d', strtotime('-6 days'));
        array_push($dates, $day);
        $day = date('Y-m-d', strtotime('-7 days'));
        array_push($dates, $day);


        $arrlength = count($dates);

        $date = [];
        for($i=0; $i < $arrlength; $i++){

            $quests = $userRepository->findByDateCreated($dates[$i]);
            $count = count($quests);


            array_push($date, $count);
        }

        return $this->render('admin/user_index.html.twig', [
            'users' => $userRepository->findAll(),
            'dates' => $date
        ]);
    }

    /**
     * @Route("/poll", name="admin_poll_index")
     */
    public function poll(PollRepository $pollRepository): Response
    {
        return $this->render('admin/poll_index.html.twig', [
            'polls' => $pollRepository->findAll(),
        ]);
    }

    /**
     * @Route("/proposed", name="admin_proposed_index")
     */
    public function proposed(ProposedPollRepository $proposedPollRepository): Response
    {
        return $this->render('admin/proposed_index.html.twig', [
            'proposed_polls' => $proposedPollRepository->findAll(),
        ]);
    }

    /**
     * @Route("/vote", name="admin_vote_index")
     */
    public function vote(VoteRepository $voteRepository): Response
    {
        return $this->render('admin/vote_index.html.twig', [
            'votes' => $voteRepository->findAll(),
        ]);
    }
}