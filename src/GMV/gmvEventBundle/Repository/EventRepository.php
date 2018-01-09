<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 30.10.2017
 * Time: 23:44
 */

namespace GMV\gmvEventBundle\Repository;
use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    //Получить мероприятия пользователя
    public function createQueryFindAllUser($UserId)
    {
      return $this->createQueryBuilder('e')
            ->andWhere('e.user = :user')
            ->setParameter('user',$UserId)
            ->getQuery();
     }

    //Участие в мероприятиях одного пользователя
    public function findAllParticipantsUser($UserId)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.participantsEvents','p','WITH','p.event = e.id')
            ->where('p.user = :user')
            ->setParameter('user',$UserId)
            ->getQuery()
            ->execute();
    }

}