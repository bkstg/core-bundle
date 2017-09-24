<?php

namespace Bkstg\CoreBundle\Security;

use Bkstg\CoreBundle\Entity\Profile;
use Bkstg\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProfileVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    private $decision_manager;

    public function __construct(AccessDecisionManagerInterface $decision_manager)
    {
        $this->decision_manager = $decision_manager;
    }


    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        if (!$subject instanceof Profile) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->decision_manager->decide($token, array('ROLE_ADMIN'))) {
            return true;
        }

        $profile = $subject;

        switch ($attribute) {
            case self::VIEW:
                return true;
            case self::EDIT:
                return $user === $profile->getAuthor();
        }

        return false;
    }
}
