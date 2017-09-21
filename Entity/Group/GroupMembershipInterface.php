<?php

namespace Bkstg\CoreBundle\Entity\Group;

use Symfony\Component\Security\Core\User\UserInterface;

interface GroupMembershipInterface
{
    const GROUP_ROLE_PREFIX = 'GROUP_ROLE_';
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 0;

    public function getRoles();
    public function addRole(string $role);
    public function removeRole(string $role);
    public function hasRole(string $role);

    public function getStatus();
    public function setStatus(int $status);
    public function isActive();

    public function getMember();
    public function setMember(GroupMemberInterface $member);

    public function getGroup();
    public function setGroup(GroupInterface $group);

    public function getExpiry();
    public function setExpiry(\DateTime $expiry = null);
    public function isExpired();
}
