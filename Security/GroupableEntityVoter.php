<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Security;

use Bkstg\CoreBundle\Model\ActiveInterface;
use Bkstg\CoreBundle\Model\ExpirableInterface;
use Bkstg\CoreBundle\User\UserInterface;
use MidnightLuke\GroupSecurityBundle\Model\GroupableInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class GroupableEntityVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected $decision_manager;

    /**
     * Build a new groupable entity voter.
     *
     * @param AccessDecisionManagerInterface $decision_manager The decision manager service.
     */
    public function __construct(AccessDecisionManagerInterface $decision_manager)
    {
        $this->decision_manager = $decision_manager;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed          $attribute The attribute to vote on.
     * @param mixed          $subject   The subject to vote on.
     * @param TokenInterface $token     The user token.
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $groupable = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($groupable, $token);
            case self::EDIT:
                return $this->canEdit($groupable, $token);
        }
    }

    /**
     * Grants edit access to group editors for groupable entities.
     *
     * @param GroupableInterface $groupable The groupable subject.
     * @param TokenInterface     $token     The user token.
     *
     * @return bool
     */
    public function canEdit(GroupableInterface $groupable, TokenInterface $token): bool
    {
        $user = $token->getUser();
        foreach ($groupable->getGroups() as $group) {
            if ($this->decision_manager->decide($token, ['GROUP_ROLE_EDITOR'], $group)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Grants view access to group members for groupable entities.
     *
     * @param GroupableInterface $groupable The groubale subject.
     * @param TokenInterface     $token     The user token.
     *
     * @return bool
     */
    public function canView(GroupableInterface $groupable, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Expirable entities can only be viewed by editors if expired.
        if ($groupable instanceof ExpirableInterface
            && $groupable->isExpired()) {
            $required_role = 'GROUP_ROLE_EDITOR';

        // Active entities can only be viewed by editors if not active.
        } elseif ($groupable instanceof ActiveInterface
            && !$groupable->isActive()) {
            $required_role = 'GROUP_ROLE_EDITOR';

        // Entity can be viewed by group users.
        } else {
            $required_role = 'GROUP_ROLE_USER';
        }

        // Iterate over groups and check access.
        foreach ($groupable->getGroups() as $group) {
            if ($this->decision_manager->decide($token, [$required_role], $group)) {
                return true;
            }
        }

        return false;
    }
}
