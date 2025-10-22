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
// REQUETE QUERY BUILDER 
    //    /**
    //     * @return Author[] Returns an array of Author objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()    
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Author
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findAllAuthosQB(): array
    {
        $req = $this->createQueryBuilder('p')
        ->andWhere('p.username LIKE :username')
        ->setParameter('username ','%T')
        ->orderBy('p.email' , 'DESC')
        ->getQuery()->getResult();
        return $req ;
        //nous avons utilisé les parametres nommés 
    }
    // src/Repository/AuthorRepository.php
public function listAuthorByEmail(): array
{
    return $this->createQueryBuilder('p')
        ->orderBy('a.email', 'ASC')
        ->getQuery()
        ->getResult();
        return $rep;
}



}
