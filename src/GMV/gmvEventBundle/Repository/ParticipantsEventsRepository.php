<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 31.10.2017
 * Time: 17:14
 */

namespace GMV\gmvEventBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ParticipantsEventsRepository extends EntityRepository
{

    public function findAllUser($UserId)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user_id = :user')
            ->setParameter('user',$UserId)
            ->getQuery()
            ->execute();
    }
//найти участника мероприятия
    public function findUserEvent($UserId,$EventId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.event','e','WITH','e.id = :id_event')
            ->innerJoin('p.user','u','WITH','u.id = :id_user')
            ->setParameter('id_event',$EventId)
            ->setParameter('id_user',$UserId)
            ->getQuery()
            ->execute();
    }


}