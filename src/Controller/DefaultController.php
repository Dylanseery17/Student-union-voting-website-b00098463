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

    /**
     * @Route("/people", name="default_people")
     */
    public function people()
    {
        return $this->render('default/people.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }



//    /**
//     * Link to this controller to start the "connect" process
//     *
//     * @Route("/connect/google", name="connect_google")
//     * @param ClientRegistry $clientRegistry
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse
//     */
//    public function connectAction(ClientRegistry $clientRegistry)
//    {
//        return $clientRegistry
//            ->getClient('google') // key used in config/packages/knpu_oauth2_client.yaml
//            ->redirect()
//            ;
//    }
//
//    /**
//     * Facebook redirects to back here afterwards
//     *
//     * @Route("/connect/google/check", name="connect_google_check")
//     * @param Request $request
//     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
//     */
//    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
//    {
//        // ** if you want to *authenticate* the user, then
//        // leave this method blank and create a Guard authenticator
//        // (read below)
//
//        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient $client */
//        $client = $clientRegistry->getClient('google');
//
//        try {
//            // the exact class depends on which provider you're using
//            /** @var \League\OAuth2\Client\Provider\FacebookUser $user */
//            $user = $client->fetchUser();
//
//            // do something with all this new power!
//            // e.g. $name = $user->getFirstName();
//            var_dump($user); die;
//            // ...
//        } catch (IdentityProviderException $e) {
//            // something went wrong!
//            // probably you should return the reason to the user
//            var_dump($e->getMessage()); die;
//        }
//
//    }

}
