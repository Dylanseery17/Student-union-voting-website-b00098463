<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Aws\Credentials\CredentialProvider;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;


class RegistrationController extends AbstractController
{

    /**
     * @var string
     */
    private $tmpFolderPath;

    public function setTmpFolderPath(string $tmpFolderPath): void
    {
        $this->tmpFolderPath = $tmpFolderPath;
    }
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $request->files->get('registration_form')['Upload_Image'];
            $uploads_directory = $this->getParameter('uploads_profile');

            
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('Password')->getData()
                )
            );

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

            $user->setDatecreated(new \DateTime());

            $user->setRoles(['ROLE_USER']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
