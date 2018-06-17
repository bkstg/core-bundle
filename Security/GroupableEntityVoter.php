<?php

namespace Bkstg\CoreBundle\Security;

use Bkstg\CoreBundle\User\UserInterface;
use Bkstg\NoticeBoardBundle\Entity\Post;
use MidnightLuke\GroupSecurityBundle\Model\GroupableInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * This class should be extended and overridden for groupable entities.
 *
 * Provides a simple base for groupable entities where group users can view
 * entities within the group, while group editors can edit entities.
 */
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
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
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
     */
    public function canEdit(GroupableInterface $groupable, TokenInterface $token)
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
     */
    public function canView(GroupableInterface $groupable, TokenInterface $token)
    {
        $user = $token->getUser();
        foreach ($groupable->getGroups() as $group) {
            if ($this->decision_manager->decide($token, ['GROUP_ROLE_USER'], $group)) {
                return true;
            }
        }
        return false;
    }
}
