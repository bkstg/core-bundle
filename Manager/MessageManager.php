<?php

namespace Bkstg\CoreBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Bkstg\CoreBundle\Entity\Message;
use Bkstg\coreBundle\Entity\User;

class MessageManager
{
    private $em;        // EntityManager
    private $router;    // Router
    private $token;     // TokenStorage

    /**
     * Constructor function, accepts services from dependency injection
     * container.
     */
    public function __construct(EntityManager $em, Router $router, TokenStorage $token)
    {
        // set services we will need in this manager
        $this->em = $em;
        $this->router = $router;
        $this->token = $token;
    }

    /**
     * Creates a new message and stores it in the database.
     */
    public function createMessage($body, $glyph = null, $link_path = null, $link_args = null, $reference_type = null, $reference_entity = null)
    {
        $message = new Message();
        $message->setBody($body);
        $message->setGlyph($glyph);
        $message->setLinkPath($link_path);
        $message->setLinkArgs($link_args);
        $message->setReferencedEntityType($reference_type);
        $message->setReferencedEntityId($reference_entity->getId());

        $this->em->persist($message);
        $this->em->flush();
    }

    /**
     * Marks passed messages as seen by the passed user.
     */
    public function markSeen(User $user, array $messages)
    {
        // loop over messages and mark as seen if not seen yet
        foreach ($messages as $message) {
            if (!$message->getSeenBy()->contains($user)) {
                $message->addSeenBy($user);
                $this->em->persist($message);

                // marks message as new for theme function
                $message->new = true;
            } else {
                $message->new = false;
            }
        }

        // flush the entity manager
        $this->em->flush();
    }

    /**
     * Returns messages, optionally marking them as seen by the current user.
     */
    public function getMessages($mark = true, User $user = null)
    {
        // user was not passed, used the token
        if ($user === null) {
            $user = $this->token->getToken()->getUser();
        }

        // get five latest messages
        $query = $this->em
            ->createQuery('SELECT m FROM BkstgCoreBundle:Message m ORDER BY m.created DESC')
            ->setMaxResults(5);
        $messages = $query->getResult();

        // mark them as seen by current user
        if ($mark) {
            $this->markSeen($user, $messages);
        }

        return $messages;
    }

    /**
     * Returns a link for a message.
     */
    public function getLink(Message $message)
    {
        if ($message->getLinkPath()) {
            return $this->router->generate($message->getLinkPath(), $message->getLinkArgs());
        } else {
            return '#';
        }
    }

    public function getReferencedEntity($message)
    {
        // $repo = $this->em->getRepository($message->);
    }

}
