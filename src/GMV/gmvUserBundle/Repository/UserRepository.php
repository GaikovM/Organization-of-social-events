<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 01.11.2017
 * Time: 0:49
 */

namespace GMV\gmvUserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    //Получить участников мероприятия
    public function findAllUsersEvents($id)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.participantsEvents','p','WITH','p.event = :event')
            ->setParameter('event',$id)
            ->getQuery()
            ->execute();

    }

    //Получить участника мероприятия
    public function findOneUserEvent($id,$idUser)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.participantsEvents','p','WITH','p.event = :event')
            ->where('u.id = :user')
            ->setParameter('user',$idUser)
            ->setParameter('event',$id)
            ->getQuery()
            ->execute();

    }
    //найти друзей
    public function findFriendUsers($id)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.userSubFreind','f','WITH','f.userFriend = u.id')
            ->where('f.user = :user')
            ->setParameter('user',$id)
            ->getQuery()
            ->execute();
    }

    //найти подписки для формы
    public function findFriendUsers_Form($id)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.userSubFreind','f','WITH','f.userFriend = u.id')
            ->where('f.user = :user')
            ->setParameter('user',$id);
    }

    //найти участников события для формы
    public function findEventUsers_Form($id)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.participantsEvents','p','WITH','p.event = :event')
            ->setParameter('event',$id);
    }



    //найти всех участников рабочей группы события
    public function finUserWorkGroupEvent($idEvent)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.workinggroup','w','WITH','w.event = :event')
            ->setParameter('event',$idEvent);
    }

    //найти друзей
    public function findFriendPUsers($id)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.userSubUser','f','WITH','f.user = u.id')
            ->where('f.userFriend = :user')
            ->setParameter('user',$id)
            ->getQuery()
            ->execute();
    }

}