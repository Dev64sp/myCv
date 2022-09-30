<?php

namespace App\Repository;

use App\Data\Filtre;
use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;


/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{



    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        parent::__construct($registry, Annonce::class);
    }



     /**
     * Retourne toutes les dernières annonces
     * @return void 
     */
    public function getLastAnnonces(){
        
        return $this->createQueryBuilder('a')
        ->orderBy('a.id', 'DESC')
        ->setMaxResults(8)
        ->getQuery()
        ->getResult();
    }

     /**
     * Retourne le nombre d'annonces
     * @return void 
     */
    public function getTotalAnnonces(){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)');
    
        return $query->getQuery()->getSingleScalarResult();
    }

     /**
     * Returne toutes les annonces par page
     * @return void 
     */
    public function getPaginatedAnnonces($page, $limit){
        $query = $this->createQueryBuilder('a');

        $query->orderBy('a.id')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Annonce $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Annonce $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }



    /**
     * Récupère les produits en lien avec une recherche
     * @return PaginationInterface
     */
    public function findSearch(Filtre $search): PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('a')
            ->select('c', 'a')
            ->join('a.category', 'c');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('a.title LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }


        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:category)')
                ->setParameter('category', $search->categories);
        }

        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            8   
        );

    }

}
