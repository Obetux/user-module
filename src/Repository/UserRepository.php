<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllByParams($params)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('u')
            ->from('App\Entity\User', 'u')
            ->where('1 = 1')
//            ->andWhere('ap.campaignId = :campaign')
//            ->setParameter(':campaign', 2)
            ->setMaxResults(1000)
            ->orderBy('u.id', 'DESC')
        ;

        if ((array_key_exists('username',$params)) && ($params['username'])){
            $qb->andWhere($qb->expr()->like('u.username',$qb->expr()->literal("%".$params['username']."%")));
        }

        if ((array_key_exists('campaign',$params)) && ($params['campaign'])){
            $qb->andWhere('u.campaign = :campaign')
                ->setParameter(':campaign', $params['campaign']);
        }


        try {
            //return $query->getResult($query::HYDRATE_SIMPLEOBJECT);
            return $qb->getQuery()->getArrayResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }

    }


}