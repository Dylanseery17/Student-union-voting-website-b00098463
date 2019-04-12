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

            $filename = md5(uniqid()) . '.' . 'jpg';

            if($file == null){

            }else{




            $bucket = getenv('S3_BUCKET');
            $fileName = md5(uniqid($bucket . '_', false)) . '.'. 'jpg';
            $user->setImage('/ProfilePics/'.$fileName);
            $file->move(
                '/test/',
                $fileName
            );

            try {
                $s3Client = new S3Client([
                    'region' => 'us-east-2',
                    'version' => 'latest',
                    'credentials' => CredentialProvider::env()
                ]);

                $s3Client->putObject([
                    'Bucket'     => $bucket,
                    'Key'        => $fileName,
                    'SourceFile' => '/test/' . $fileName,
                ]);

            } catch (AwsException $e) {
                echo $e->getMessage() . "\n";
            }
            }
            
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('Password')->getData()
                )
            );

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
