<?php

namespace GMV\gmvUserBundle\Repository;

use Doctrine\ORM\EntityRepository;


class SubscriptionToUserRepository extends EntityRepository
{
    //найти связанного участника
    public function findUserFreind($User,$Freind)
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.user','u','WITH','u.id = :user')
            ->innerJoin('s.userFriend','f','WITH','f.id = :freind')
            ->setParameter('user',$User->getId())
            ->setParameter('freind',$Freind->getId())
            ->getQuery()
            ->execute();
    }

    //найти связанног
    public function findFreind($User)
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.user','u','WITH','u.id = :user')
            ->setParameter('user',$User->getId())
            ->getQuery()
            ->execute();
    }

}
