<?php

namespace Bkstg\CoreBundle\Model;

use Bkstg\CoreBundle\Exception\UserHasNoRoleException;
use Bkstg\CoreBundle\Exception\UserHasRoleException;
use Bkstg\CoreBundle\Model\Group\GroupInterface;
use Bkstg\CoreBundle\Model\Group\GroupMemberInterface;
use Bkstg\CoreBundle\Model\Group\GroupMembershipInterface;

class ProductionMembership implements GroupMembershipInterface
{
    private $id;
    private $group;
    private $member;
    private $roles;
    private $status;
    private $expiry;

    public function __construct()
    {
        $this->roles = [];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup(GroupInterface $group)
    {
        if (!$group instanceof Production) {
            throw new GroupTypeNotSupportedException();
        }
        $this->group = $group;
        return $this;
    }

    public function getMember()
    {
        return $this->member;
    }

    public function setMember(GroupMemberInterface $member)
    {
        if (!$member instanceof User) {
            throw new MemberTypeNotSupportedException();
        }
        $this->member = $member;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function addRole(string $role)
    {
        if (substr($role, 0, 11) != GroupMembershipInterface::GROUP_ROLE_PREFIX) {
            throw new InvalidGroupRoleException();
        }
        if ($this->hasRole($role)) {
            throw new UserHasRoleException();
        }
        $this->roles[] = $role;
        return $this;
    }

    public function removeRole(string $role)
    {
        if (!$this->hasRole($role)) {
            throw new UserHasNoRoleException();
        }
        unset($this->roles[array_search($role, $this->roles)]);
        return $this;
    }

    public function hasRole(string $role)
    {
        return in_array($role, $this->roles);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        if (!in_array($status, [
            GroupMembershipInterface::STATUS_ACTIVE,
            GroupMembershipInterface::STATUS_BLOCKED,
        ])) {
            throw new InvalidStatusException();
        }
        $this->status = $status;
        return $this;
    }

    public function getExpiry()
    {
        return $this->expiry;
    }

    public function setExpiry(\DateTime $expiry = null)
    {
        $this->expiry = $expiry;
        return $this;
    }

    public function isExpired()
    {
        // No expiry on this membership.
        if ($this->expiry === null) {
            return false;
        }

        $now = new \DateTime();
        return ($now < $this->expiry);
    }

    public function isActive()
    {
        if ($this->isExpired()) {
            return false;
        }
        return ($this->status == GroupMembershipInterface::STATUS_ACTIVE);
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return ProductionMembership
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }
}
