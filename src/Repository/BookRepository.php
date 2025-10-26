<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
      public function findBooksPublishedBetween($startDate, $endDate): array
    {
        // On récupère l'Entity Manager
        $em = $this->getEntityManager();

        // On écrit la requête DQL en ciblant l'Entité "Book"
        $dql = 'SELECT b
                FROM App\Entity\Book b
                WHERE b.published = :published
                AND b.publicationDate BETWEEN :start AND :end
                ORDER BY b.publicationDate ASC';

        // On crée la requête
        $query = $em->createQuery($dql);

        // On définit les paramètres pour sécuriser la requête
        $query->setParameter('published', true);
        $query->setParameter('start', $startDate);
        $query->setParameter('end', $endDate);

        // On retourne le résultat (une liste d'objets Book)
        return $query->getResult();
    }

   public function searchBookByAuthorDQL($search): array
    {
        // On écrit la requête DQL en ciblant les entités PHP
        $dql = 'SELECT b
                FROM App\Entity\Book b
                JOIN b.author a
                WHERE a.username LIKE :author_name';
        
        $query = $this->getEntityManager()->createQuery($dql);
        
        $query->setParameter('author_name', '%' . $search . '%');

        return $query->getResult();
    }







}
