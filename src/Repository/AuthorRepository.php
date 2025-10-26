<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }
    
    /**
     * Retourne la liste des auteurs triés par email par ordre croissant.
     * Utilisé pour la route ListAuthorsByEmail.
     * @return Author[] Returns an array of Author objects
     */
    public function ListAuthorsByEmail(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.email', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Supprime tous les auteurs qui n'ont aucun livre associé (nb_books = 0).
     * @return int Le nombre d'auteurs supprimés.
     */
    public function deleteAuthorsWithZeroBooks(): int
    {
        $em = $this->getEntityManager();
        
        // Requête DQL pour supprimer les auteurs dont la collection 'books' est vide.
        $dql = "DELETE FROM App\Entity\Author a 
                WHERE a.books IS EMPTY";

        $query = $em->createQuery($dql);
        
        // execute() renvoie le nombre de lignes affectées.
        return $query->execute();
    }
    
    /*
     * Exemples de méthodes de recherche personnalisées avec QueryBuilder :
     * public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneBySomeField($value): ?Author
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
