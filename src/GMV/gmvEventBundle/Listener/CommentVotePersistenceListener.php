<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 01.11.2017
 * Time: 12:40
 */

namespace GMV\gmvEventBundle\Listener;

use FOS\CommentBundle\Event\VotePersistEvent;
use FOS\CommentBundle\Events;
use FOS\CommentBundle\Model\SignedVoteInterface;
use FOS\CommentBundle\Model\VoteManagerInterface;
use GMV\gmvUserBundle\Entity\gUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;


class CommentVotePersistenceListener  implements EventSubscriberInterface
{

    /**
     * @var VoteManagerInterface
     */
    protected $voteManager;
    /**
     * @var AuthorizationChecker
     */
    protected $context;

    /**
     * @var TokenStorage
     */
    protected $token;

    /**
     * @param VoteManagerInterface $voteManager
     * @param AuthorizationChecker $context
     * @param AuthorizationChecker $token
     */
    function __construct(VoteManagerInterface $voteManager, AuthorizationChecker $context,TokenStorage $token)
    {
        $this->voteManager = $voteManager;
        $this->context = $context;
        $this->token = $token;

    }
    /**
     * Assign owner for comment's vote in a case if it is missed
     * Actually this is functional copy of VoteBlamerListener::blame
     * but we need to run it before vote validation will occur
     *
     * @return void
     * @see FOS\CommentBundle\EventListener\VoteBlamerListener::blame
     */
    public function assignCommentVoteOwner(VotePersistEvent $event)
    {
        if (!$this->context->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED)) {
            return;
        }
        /** @var $vote SignedVoteInterface */
        $vote = $event->getVote();
        if ($vote->getVoter() === null) {
            $vote->setVoter($this->token->getToken()->getUser());
        }
    }
    /**
     * Listener for comments' votes persistence to avoid voting for own comments
     * and multiple voting for comments
     *
     * @param VotePersistEvent $event
     * @return void
     */
    public function avoidIncorrectVoting(VotePersistEvent $event)
    {
        try {
            if (!$this->context->isGranted(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED)) {
                throw new \Exception('Avoid voting if user is not authenticated');
            }
            /** @var $vote SignedVoteInterface */
            $vote = $event->getVote();
            /** @var $user User */
            $user = $this->token->getToken()->getUser();
            if ($vote->getVoter() !== $user) {
                throw new \Exception('Attempt to vote for different user');
                dump('sad');
            }
            if ($vote->getComment()->getAuthor() === $user) {
                throw new \Exception('Attempt to vote for own comment');
            }
            $existingVote = $this->voteManager->findVoteBy(array(
                'comment' => $vote->getComment(),
                'voter'   => $user,
            ));
            if ($existingVote) {
                throw new \Exception('Attempt to vote multiple times for same comment');
                dump('sfff');
            }
        } catch (\Exception $e) {
            $event->abortPersistence();
            $event->stopPropagation();
        }
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::VOTE_PRE_PERSIST => array(
                array('assignCommentVoteOwner', 20),
                array('avoidIncorrectVoting', 10)
            )
        );
    }
}