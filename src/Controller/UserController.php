<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Aws\Credentials\CredentialProvider;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;


/**
 * @Route("admin/user")*
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{


    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request , UserPasswordEncoderInterface $passwordEncoder ): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $request->files->get('user')['Upload_Image'];
            print_r($file);
            $uploads_directory = $this->getParameter('uploads_profile');

            if($file == null){

            }else {


                $bucketName = 'studentunionpolling';
                $IAM_KEY = 'AKIAQ4NT35YJCXH6LPNC';
                $IAM_SECRET = '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';

                $fileName = md5(uniqid('studentunionpolling' . '_', false)) . '.' . 'jpg';

                $file->move(
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
                $keyName = 'ProfilePic/' . $fileName;
                $pathInS3 = 'https://s3.eu-west-1.amazonaws.com/' . $bucketName . '/' . $keyName;
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
                    $user->setImage($pathInS3);
                } catch (S3Exception $e) {
                    die('Error:' . $e->getMessage());
                } catch (Exception $e) {
                    die('Error:' . $e->getMessage());
                }
                echo 'Done';
            }

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user , UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $request->files->get('user')['Upload_Image'];
            print_r($file);
            $uploads_directory = $this->getParameter('uploads_profile');

            if($file == null){

            }else {

                $bucketName = 'studentunionpolling';
                $IAM_KEY = 'AKIAQ4NT35YJCXH6LPNC';
                $IAM_SECRET = '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';

                $fileName = md5(uniqid('studentunionpolling' . '_', false)) . '.' . 'jpg';

                $file->move(
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
                $keyName = 'ProfilePic/' . $fileName;
                $pathInS3 = 'https://s3.eu-west-1.amazonaws.com/' . $bucketName . '/' . $keyName;
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
                    $user->setImage($pathInS3);
                } catch (S3Exception $e) {
                    die('Error:' . $e->getMessage());
                } catch (Exception $e) {
                    die('Error:' . $e->getMessage());
                }
                echo 'Done';
            }


            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setRoles(['ROLE_USER']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,                                    
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
