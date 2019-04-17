<?php

namespace App\Controller;

use App\Entity\ProposedPoll;
use App\Entity\Poll;
use App\Entity\Support;
use App\Form\ProposedPollType;
use App\Form\ActivateProposedPollType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\ProposedSupport;
use App\Repository\SupportRepository;
use Aws\S3\S3Client;
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
            foreach ($file as $files) {

                $fileName = md5(uniqid('studentunionpolling' . '_', false)) . '.' . 'jpg';


                if ($files == null) {

                } else {


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
                                'SourceFile' => $uploads_directory . '/' . $fileName,
                                'StorageClass' => 'REDUCED_REDUNDANCY'
                            )
                        );
                    } catch (S3Exception $e) {
                        die('Error:' . $e->getMessage());
                    } catch (Exception $e) {
                        die('Error:' . $e->getMessage());
                    }
                }
                $proposedPoll->setImage($img);
                echo 'Done';
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proposedPoll);
            $entityManager->flush();

            return $this->redirectToRoute('proposed_poll_show', [
                'id' => $proposedPoll->getId(),
            ]);
        }
        return $this->render('proposed_poll/new.html.twig', [
            'proposed_poll' => $proposedPoll,
            'form' => $form->createView(),
        ]);
    }

        /**
         * @Route("/activate/{id}", name="proposed_poll_activate", methods={"GET","POST","DELETE"})
         * @IsGranted("ROLE_ADMIN")
         */
    public function activate(Request $request, ProposedPoll $proposedPoll): Response
    {
        $poll = new Poll();
        $form = $this->createForm(ActivateProposedPollType::class, $proposedPoll);
        $form->handleRequest($request);

        $manager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $proposed = $manager->getRepository('App:ProposedPoll')->find($proposedPoll);

            $enddate = $form["Enddate"]->getData();
            var_dump($enddate);
            $finalenddate = \DateTime::createFromFormat('Y-m-d', $enddate);
            $poll->setName($proposed->getName());
            $poll->setOptions($proposed->getOptions());
            $poll->setStartdate(new \DateTime());
            $poll->setEnddate($finalenddate);
            $poll->setDescription($proposed->getDescription());
            $poll->setImage($proposed->getImage());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($poll);
            $entityManager->remove($proposedPoll);
            $entityManager->flush();

            return $this->redirectToRoute('poll_show', [
                'id' => $poll->getId(),
            ]);
        }

        return $this->render('proposed_poll/activate.html.twig', [
            'proposed_poll' => $proposedPoll,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="proposed_poll_show" )
     */
    public function show(Request $request, ProposedPoll $proposedPoll ,SupportRepository $supportRepository): Response
    {
        $support = new Support();
        $form = $this->createForm(ProposedSupport::class, $proposedPoll);
        $form->handleRequest($request);

        $support_poll = $supportRepository->findBy(array('Proposed' => $proposedPoll));

        $manager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $usr = $_POST['user'];
            $usr = (int) $usr;
            $pol = $_POST['poll'];
            $pol = (int) $pol;
            $spoll = $manager->getRepository('App:ProposedPoll')->find($pol);
            $suser = $manager->getRepository('App:User')->find($usr);
            $proposed = $manager->getRepository('App:ProposedPoll')->find($proposedPoll);

            $didyousupport = $supportRepository->findByUser($spoll,$suser);
            $sup = count($didyousupport);

            if($sup == 1){
                return $this->redirectToRoute('proposed_poll_show', [
                    'id' => $proposedPoll->getId(),
                ]);
            }
            if($sup != 1){
            $support->setProposed($proposed);
            $support->setUser($suser);
            $proposedPoll = $proposed->setSupport($proposed->getSupport() + 1 );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($support);
            $entityManager->persist($proposedPoll);
            $entityManager->flush();

                return $this->redirectToRoute('proposed_poll_show', [
                    'id' => $proposedPoll->getId(),
                ]);
            }

        }

        return $this->render('proposed_poll/show.html.twig', [
            'proposed_poll' => $proposedPoll,
            'form' => $form->createView(),
            'supports' => $support_poll,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="proposed_poll_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
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
                }$proposedPoll->setImage($img);
                echo 'Done';
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
     * @IsGranted("ROLE_ADMIN")
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
