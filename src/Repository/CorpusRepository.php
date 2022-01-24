<?php

namespace App\Repository;

use App\Entity\Corpus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Corpus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Corpus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Corpus[]    findAll()
 * @method Corpus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)

 */
class CorpusRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Corpus::class);
    }

    public function findByName($value, $user)
    {
        return dump($this->createQueryBuilder('c')
            ->andWhere('c.nom LIKE :nom')
            ->andWhere('c.hasUser = :user')
            ->setParameter('user',  $user->getId())

            ->setParameter('nom', '%' . $value . '%')

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
