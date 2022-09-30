<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use App\Repository\AnnonceRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Annonce;
use App\Entity\Category;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Persistence\ManagerRegistry;

class AnnonceController extends AbstractController
{
  /**
   * @var EntityManagerInterface
   */
  private $userRepository;
  private $entityManager;
  
  public function __construct(ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager, AnnonceRepository $annonceRepository, CategoryRepository $categoryRepository){
    $this->entityManager = $entityManager;
    $this->annonceRepository = $annonceRepository;
    $this->categoryRepository = $categoryRepository;
    $this->managerRegistry = $managerRegistry;

  }


  
  /**
   * @Route("/annonce/{id}", name="annonce")
   * @param int $id
   */
  public function annonce($id): Response
  {
    $annonce = $this->annonceRepository->findOneBy(['id' => $id]);
    $category = $annonce->getCategory()->getContent();
    $description = $annonce->getDescription();
    $titre = $annonce->getTitle();
    $image = $annonce->getImage();
    $annoncesNotSold = [];

    return $this->render('home/annonce.html.twig', [
      'controller_name' => 'AnnonceController',
      'annonce' => $annonce,
      'titre' => $titre,
      'category' => $category,
      'description' => $description,
    ]);
  }
}