<?php

namespace Bkstg\CoreBundle\Model\Group;

interface GroupMemberInterface
{
    public function getMemberships();
    public function addMembership(GroupMembershipInterface $membership);
    public function removeMembership(GroupMembershipInterface $membership);
    public function hasMembership(GroupMembershipInterface $membership);
}
