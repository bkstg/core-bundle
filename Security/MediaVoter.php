<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Security;

use Bkstg\CoreBundle\Security\GroupableEntityVoter;
use Bkstg\CoreBundle\Entity\Media;
use MidnightLuke\GroupSecurityBundle\Model\GroupableInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MediaVoter extends GroupableEntityVoter
{
    /**
     * {@inheritdoc}
     *
     * @param mixed $attribute The attribute to vote on.
     * @param mixed $subject   The subject to vote on.
     *
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        // Only vote on view and edit.
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // Only vote on media objects.
        if (!$subject instanceof Media) {
            return false;
        }

        return true;
    }

    /**
     * Override view handler, if this is not grouped allow access.
     *
     * @param GroupableInterface $media The media to vote on.
     * @param TokenInterface     $token The token to vote on.
     *
     * @return bool
     */
    public function canView(GroupableInterface $media, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (0 == count($media->getGroups())) {
            return true;
        }

        return parent::canView($media, $token);
    }
}
