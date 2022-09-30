<?php

namespace App\Controller;

use App\Data\filtre;
use App\Entity\Image;
use App\Entity\Annonce;
use App\Form\ContactType;
use App\Form\SearchAnnonceType;
use App\Form\FiltreFormType;
use App\Service\CallApiService;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\MailJet\Mail;
use App\Service\ApiUser;
use App\Form\ForgetPasswordForm;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppCustomAuthenticator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use \Mailjet\Resources;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;


class HomeController extends AbstractController
{
  private $entityManager;
  private $annonceRepository;

  public function __construct(ManagerRegistry $managerRegistry,EntityManagerInterface $entityManager, AnnonceRepository $annonceRepository, KernelInterface $kernel)
  {   
    $this->annonceRepository = $annonceRepository;
    $this->entityManager = $entityManager;
    $this->managerRegistry = $managerRegistry;



  }
   
  /**
   * @Route("/", name="app_homepage")
   * @param Annonce $annonce
   */
  public function index(AnnonceRepository $annoncesRepository, Request $request, PaginatorInterface $paginator): Response
  {
    $limit = 10;
    $page = (int)$request->query->get("page", 1);
    $total = $annoncesRepository->getTotalAnnonces();
    $lastAnnonces = $annoncesRepository->getLastAnnonces();

    $annoncesAll = $this->managerRegistry->getRepository(Annonce::class)->findAll();
  
    
    $annonces = [];

      
    return $this->render('home/index.html.twig', [
      'controller_name' => 'HomeController',
      'lastAnnonces' => $lastAnnonces,
    ]);
  }

  /**
   * @Route("/contact", name="contact")
   */
  public function contact(Request $request,MailerInterface $mailer, VerifyEmailHelperInterface $verifyEmailHelper): Response
  {
    $form = $this->createForm(ContactType::class);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {

      $user = $form->get('name')->getData();
      $mail = $form->get('email')->getData();
      $object = $form->get('subject')->getData();
      $message = $form->get('message')->getData();
        // do anything else you need here, like send an email
      
            // creation du mail
        $email = (new TemplatedEmail())
        ->from($mail)
        -> to('dev64splatoon@gmail.com')
  
        //->cc('cc@example.com')
        ->bcc('dev64splatoon@gmail.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Message reçu!')
        // path of the Twig template to render
        ->htmlTemplate('emails/message.html.twig')
  
        // pass variables (name => value) to the template
        ->context([
            'user' => $user ,
            'message' => $message,
            'object' => $object,
            'mail' => $mail,
        ]);
  
        $mailer->send($email);
  
        return $this->render('home/contact.html.twig', [
          'form' => $form->createView(),
          'message' => 'Votre message est envoyé! Je vous répondrais dès que possible.'
        ]);
    
      };

    return $this->render('home/contact.html.twig', [
      'form' => $form->createView(),
      'message' => ''
    ]);
  }


    /**
   * @Route("/products", name="products")
   */
  public function products(AnnonceRepository $annoncesRepository, Request $request, PaginatorInterface $paginator): Response
  {

    $data = new filtre();
    $data->page = $request->get('page', 1);
    $form = $this->createForm(FiltreFormType::class, $data);
    $form->handleRequest($request);
    $totalAnnonces = $annoncesRepository->getTotalAnnonces();



    $annonces = $annoncesRepository->findSearch($data);
    $totalAnnonces = count($annonces);

    return $this->render('home/produits.html.twig', [
      'controller_name' => 'HomeController',
      'annonces' => $annonces,
      'total' => $totalAnnonces,
      'form' => $form->createView()
    ]);
  }


  /**
    * @Route("/error404", name="error404")
    */
  public function error404(): Response
  {
    return $this->render('error404/error404.html.twig', [
      'controller_name' => 'HomeController'
    ]);
  }

}