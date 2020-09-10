<?php

namespace App\Repository;

use App\Entity\KitValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method KitValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method KitValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method KitValue[]    findAll()
 * @method KitValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KitValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KitValue::class);
    }

    // /**
    //  * @return KitValue[] Returns an array of KitValue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?KitValue
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
