<?php

namespace App\Repository;

use App\Entity\Evaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Evaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evaluation[]    findAll()
 * @method Evaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evaluation::class);
    }

    
    public function findByName($value, $user)
    {
        return dump($this->createQueryBuilder('c')
            ->andWhere('c.nom LIKE :nom')
            ->setParameter('nom', '%' . $value . '%')
             ->andWhere('c.hasUser = :user')
            ->setParameter('user',  $user->getId())

            ->getQuery()
            ->getResult());
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
