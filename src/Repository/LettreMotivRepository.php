<?php

namespace App\Repository;

use App\Entity\LettreMotiv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LettreMotiv|null find($id, $lockMode = null, $lockVersion = null)
 * @method LettreMotiv|null findOneBy(array $criteria, array $orderBy = null)
 * @method LettreMotiv[]    findAll()
 * @method LettreMotiv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LettreMotivRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LettreMotiv::class);
    }

    // /**
    //  * @return LettreMotiv[] Returns an array of LettreMotiv objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LettreMotiv
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
