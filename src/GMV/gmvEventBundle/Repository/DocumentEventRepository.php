<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 30.10.2017
 * Time: 23:44
 */

namespace GMV\gmvEventBundle\Repository;
use Doctrine\ORM\EntityRepository;

class DocumentEventRepository extends EntityRepository
{

    //Получить мероприятия пользователя
    public function findAllDocEvent($eventId)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.event','e','WITH','e.id = :id_event')
            ->setParameter('id_event',$eventId)
            ->getQuery()
            ->execute();
    }

}