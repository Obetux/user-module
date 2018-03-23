<?php

namespace App\Repository;

use App\Entity\LoginHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class LoginHistoryRepository
 * @package App\Repository
 */
class LoginHistoryRepository extends ServiceEntityRepository
{
    /**
     * LoginHistoryRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LoginHistory::class);
    }

    /**
     * @param $ipAddress
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countFailLogin($ipAddress)
    {
        $date = new \DateTime();
        $interval = new \DateInterval("PT15M");
//        $interval->invert = 1;
        $date->sub($interval);

        return $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->where('l.ipAddress = :ip')->setParameter('ip', $ipAddress)
            ->andWhere('l.state = :state')->setParameter('state', false)
            ->andWhere('l.created > :date')->setParameter('date', $date)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('l')
            ->where('l.something = :value')->setParameter('value', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
