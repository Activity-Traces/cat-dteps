<?php

namespace App\Repository;

use App\Entity\Resource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Resource|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resource|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resource[]    findAll()
 * @method Resource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resource::class);
    }

    public function findByName($value, $user)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.nom LIKE :nom')
            ->andWhere('c.hasUser = :user')
            ->setParameter('user',  $user->getId())
            ->setParameter('nom', '%' . $value . '%')
           
            ->getQuery()
            ->getResult();
    }

    public function deleteAll($user)
    {
        return $this->createQueryBuilder('c')
            ->delete()
            ->andWhere('c.hasUser = :user')
            ->setParameter('user',  $user->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
