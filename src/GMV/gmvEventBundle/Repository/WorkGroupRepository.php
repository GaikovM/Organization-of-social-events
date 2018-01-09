<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 30.10.2017
 * Time: 23:44
 */

namespace GMV\gmvEventBundle\Repository;
use Doctrine\ORM\EntityRepository;

class WorkGroupRepository extends EntityRepository
{
    //Получить все строки рабочей группы на мероприятие
    public function FindEventGroup($UserEvent)
    {
      return $this->createQueryBuilder('w')
          ->innerJoin('w.event','e','WITH','e.id = :id')
          ->setParameter('id',$UserEvent)
          ->getQuery()
          ->execute();
     }

}