<?php

namespace Bkstg\CoreBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Bkstg\CoreBundle\Entity\User;

class UserToNumberTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($user)
    {
        if (null === $user) {
            return "";
        }

        return $user->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $id
     *
     * @return Issue|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $user = $this->om
            ->getRepository('BkstgCoreBundle:User')
            ->findOneBy(array('id' => $id))
        ;

        if (null === $user) {
            throw new TransformationFailedException(sprintf(
                'A User with id "%s" does not exist!',
                $id
            ));
        }

        return $user;
    }
}
