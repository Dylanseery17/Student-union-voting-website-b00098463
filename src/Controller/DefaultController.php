<?php

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
use Aws\S3\S3Client;
use Doctrine\ORM\Query;


class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="default_index")
     */
    public function index(PollRepository $pollRepository ,Request $request ,ProposedPollRepository $proposedPollRepository): Response
    {
//        $client = S3Client::factory(array(
//            'key'    => '<aws access key>',
//            'secret' => '<aws secret key>'
//        ));
//
//        $images = $client->getIterator('ListObjects', array(
//            'Bucket' => $bucket,
//            'Marker' => 'folder1/gallary/',
//            //I believe marker is what would be use to say only objects in this folder. Not 100% on that.
//        ));

        $result =  $pollRepository->findByEndDate();
        $finished = $pollRepository->findByExpired();
        $proposedPoll = new ProposedPoll();
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proposedPoll);
            $entityManager->flush();

            return $this->redirectToRoute('proposed_poll_show', [
                'id' => $proposedPoll->getId(),
            ]);
        }

        return $this->render('default/index.html.twig', [
            'polls' => $result,
            'endpolls' => $finished,
            'proposed_polls' => $proposedPollRepository->findAll(),
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
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

}
