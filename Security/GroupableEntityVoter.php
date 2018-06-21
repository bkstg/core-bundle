<?php

namespace Bkstg\CoreBundle\Security;

use Bkstg\CoreBundle\User\UserInterface;
use Bkstg\NoticeBoardBundle\Entity\Post;
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
     * @param  mixed          $attribute The attribute to vote on.
     * @param  mixed          $subject   The subject to vote on.
     * @param  TokenInterface $token     The user token.
     * @return boolean
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
     * @param  GroupableInterface $groupable The groupable subject.
     * @param  TokenInterface     $token     The user token.
     * @return boolean
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
     * @param  GroupableInterface $groupable The groubale subject.
     * @param  TokenInterface     $token     The user token.
     * @return boolean
     */
    public function canView(GroupableInterface $groupable, TokenInterface $token): bool
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
