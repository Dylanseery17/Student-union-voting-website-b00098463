<?php

namespace App\Controller;

use App\Entity\Poll;
use App\Form\PollType;
use App\Entity\Vote;
use App\Entity\User;
use App\Form\VoteType;
use Aws\S3\S3Client;
use App\Repository\VoteRepository;
use App\Repository\PollRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Comments;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;


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
     * @IsGranted("ROLE_USER")
     */
    public function vote(UserInterface $userz , Request $request , SessionInterface $session, Poll $poll ,PollRepository $pollRepository , VoteRepository $voteRepository): Response
    {
        $vote = new Vote();
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);
        $row = $pollRepository->findByID($poll);
        $find = $voteRepository->findByPoll($poll);
        $count = count($find);

        $userId = $userz->getUsername();
        $didyouVote = $voteRepository->findByPollByUser($poll,$userId);
        $didyouVotecount = count($didyouVote);


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

            $this->addFlash(
                'notice',
                'Aww yeah, you successfully voted on '
            );

            return $this->redirectToRoute('poll_show', [
                'id' => $poll->getId(),
            ]);
        }
        return $this->render('vote/pollvote.html.twig', [
            'vote' => $vote,
            'form' => $form->createView(),
            'poll' => $poll,
            'count' => $count,
            'votes' => $didyouVote,
            'voted' => $didyouVotecount,
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

            $uploads_directory = $this->getParameter('uploads_directory');
            $file = $request->files->get('poll')['Upload_Image'];
            $img = [];

            foreach($file as $files){

                $fileName = md5(uniqid('studentunionpolling' . '_', false)) . '.' . 'jpg';


                if($files == null){

                }else {


                    $bucketName = 'studentunionpolling';
                    $IAM_KEY = 'AKIAQ4NT35YJCXH6LPNC';
                    $IAM_SECRET = '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';

                    $files->move(
                        $uploads_directory,
                        $fileName
                    );
                    // Connect to AWS
                    try {
                        // You may need to change the region. It will say in the URL when the bucket is open
                        // and on creation.
                        $s3 = S3Client::factory(
                            array(
                                'credentials' => array(
                                    'key' => $IAM_KEY,
                                    'secret' => $IAM_SECRET
                                ),
                                'version' => 'latest',
                                'region' => 'eu-west-1'
                            )
                        );
                    } catch (Exception $e) {
                        // We use a die, so if this fails. It stops here. Typically this is a REST call so this would
                        // return a json object.
                        die("Error: " . $e->getMessage());
                    }

                    // For this, I would generate a unqiue random string for the key name. But you can do whatever.
                    $keyName = 'Uploads/' . $fileName;
                    $pathInS3 = 'https://s3.eu-west-1.amazonaws.com/' . $bucketName . '/' . $keyName;

                    array_push($img, $pathInS3);
                    // Add it to S3
                    try {
                        // Uploaded:
                        $file = $fileName;
                        $s3->putObject(
                            array(
                                'Bucket' => $bucketName,
                                'Key' => $keyName,
                                'SourceFile' => $uploads_directory .'/'. $fileName,
                                'StorageClass' => 'REDUCED_REDUNDANCY'
                            )
                        );
                    } catch (S3Exception $e) {
                        die('Error:' . $e->getMessage());
                    } catch (Exception $e) {
                        die('Error:' . $e->getMessage());
                    }
                }$poll->setImage($img);
                echo 'Done';
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($poll);
            $entityManager->flush();

            return $this->redirectToRoute('poll_show', [
                'id' => $poll->getId(),
            ]);
        }

        return $this->render('poll/new.html.twig', [
            'poll' => $poll,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="poll_show", methods={"GET","POST"})
     */
    public function show(Request $request,Poll $poll,  VoteRepository $voteRepository , CommentsRepository $commentsRepository ,PollRepository $pollRepository ): Response
    {
        $vote = new Vote();
        $find = $voteRepository->findByAns($poll);
        $countx = count($find);

//        Comment Section for Poll
        $comment = new Comments();
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        $expired = $pollRepository->findByExpiredID($poll);
        $expired = count($expired);

        $manager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $usr = $_POST['user'];
            $usr = (int) $usr;
            $pol = $_POST['poll'];
            $pol = (int) $pol;
            $cpoll = $manager->getRepository('App:Poll')->find($pol);
            $cuser = $manager->getRepository('App:User')->find($usr);


            $comm = $_POST['comment'];
            $comment->setComment($comm);
            $comment->setUser($cuser);
            $comment->setPoll($cpoll);
            $comment->setTime(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('poll_show', [
                'id' => $poll->getId(),
            ]);
        }

        $cpoll = $manager->getRepository('App:Poll')->find($poll);

        $start_date = $cpoll->getStartdate();
        $start_date = $start_date->format('Y-m-d');

        $end_date = $cpoll->getEnddate();
        $end_date = $end_date->format('Y-m-d');
        $date = new \DateTime();
        $now = new \DateTime($start_date);

        $interval = $now->diff($date);
        $days = $interval->format('%a');

        $dates = [];
        for($i=0; $i < $days; $i++){
            $day = date('Y-m-d', strtotime(''.$i.' days', strtotime($start_date)));
            array_push($dates, $day);
        }

        $dates_value = [];


        $find = array_unique($find, SORT_REGULAR);
        $count = count($find);

        $votes = [];
        foreach($find as $ans){

            $quests = $voteRepository->countByAns($poll ,$ans);
            $counta = count($quests);

            array_push($votes, $counta);
        }
//        Getting votes over dates for each answer
        foreach($find as $ans){
            $i =0;
            //This took to longggggggggggggggg
            foreach($dates as $date){
                $quests = $voteRepository->countByAnsByDate($poll ,$date ,$ans);
                if($i>0){
                $last = count($quests) + end($dates_value);
                }else{
                $last = count($quests);
                }
                $i = $i + 1;
                array_push($dates_value, $last);
            }
        }
        $display = [];
        for($i=0; $i < $count; $i++){

            $quests = $voteRepository->countByAns($poll ,$find[$i]);
            $countd = count($quests);

            $countd = $countd / $countx * 100;
            $countd = (int) $countd;

            $tmp = array('val'=>$countd);
            $src = $find[$i];
            array_push($display, $src);
            array_push($display, $tmp);
        }

        $poll_comments = $commentsRepository->findByPoll($poll);
        $comment_count = count($poll_comments);

        return $this->render('poll/show.html.twig', [
            'poll' => $poll,
            'count' => $countx,
            'answers' => $count,
            'label' => $find,
            'ans' => $votes,
            'expired' => $expired,
            'comments' => $poll_comments,
            'comments_count' => $comment_count,
            'form' => $form->createView(),
            'display' => $display,
            'daysBetween' => $dates,
            'Numberofdays' => $days,
            'votesOvertime' => $dates_value,
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

            $uploads_directory = $this->getParameter('uploads_directory');
            $file = $request->files->get('poll')['Upload_Image'];
            $img = [];

            foreach($file as $files){

                $fileName = md5(uniqid('studentunionpolling' . '_', false)) . '.' . 'jpg';


                if($files == null){

                }else {


                    $bucketName = 'studentunionpolling';
                    $IAM_KEY = 'AKIAQ4NT35YJCXH6LPNC';
                    $IAM_SECRET = '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';

                    $files->move(
                        $uploads_directory,
                        $fileName
                    );
                    // Connect to AWS
                    try {
                        // You may need to change the region. It will say in the URL when the bucket is open
                        // and on creation.
                        $s3 = S3Client::factory(
                            array(
                                'credentials' => array(
                                    'key' => $IAM_KEY,
                                    'secret' => $IAM_SECRET
                                ),
                                'version' => 'latest',
                                'region' => 'eu-west-1'
                            )
                        );
                    } catch (Exception $e) {
                        // We use a die, so if this fails. It stops here. Typically this is a REST call so this would
                        // return a json object.
                        die("Error: " . $e->getMessage());
                    }

                    // For this, I would generate a unqiue random string for the key name. But you can do whatever.
                    $keyName = 'Uploads/' . $fileName;
                    $pathInS3 = 'https://s3.eu-west-1.amazonaws.com/' . $bucketName . '/' . $keyName;

                    array_push($img, $pathInS3);
                    // Add it to S3
                    try {
                        // Uploaded:
                        $file = $fileName;
                        $s3->putObject(
                            array(
                                'Bucket' => $bucketName,
                                'Key' => $keyName,
                                'SourceFile' => $uploads_directory .'/'. $fileName,
                                'StorageClass' => 'REDUCED_REDUNDANCY'
                            )
                        );
                    } catch (S3Exception $e) {
                        die('Error:' . $e->getMessage());
                    } catch (Exception $e) {
                        die('Error:' . $e->getMessage());
                    }
                }$poll->setImage($img);
                echo 'Done';
            }

            return $this->redirectToRoute('poll_show', [
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

        return $this->redirectToRoute('admin_poll_index');
    }
}
