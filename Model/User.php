<?php

namespace Bkstg\CoreBundle\Model;

use Bkstg\CoreBundle\Exception\InvalidRoleException;
use Bkstg\CoreBundle\Exception\UserHasNoRoleException;
use Bkstg\CoreBundle\Exception\UserHasRoleException;
use Bkstg\CoreBundle\Model\Group\GroupMemberInterface;
use Bkstg\CoreBundle\Model\Group\GroupMembershipInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements AdvancedUserInterface, EquatableInterface, GroupMemberInterface
{
    const ROLE_PREFIX = 'ROLE_';
    const USER_BLOCKED = 0;
    const USER_ACTIVE = 1;

    private $id;
    private $username;
    private $email;
    private $password;
    private $roles;
    private $status;
    private $created;
    private $memberships;

    /**
     * Create a new instance of User.
     */
    public function __construct()
    {
        $this->roles = [];
        $this->memberships = new ArrayCollection();
    }

    /**
     * Get id
     * @return
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add a role.
     *
     * @param string $role The role to add.
     *
     * @throws UserHasRoleException if the user already has the specified role.
     *
     * @return $this
     */
    public function addRole(string $role)
    {
        if (substr($role, 0, 5) != self::ROLE_PREFIX) {
            throw new InvalidRoleException();
        }
        if ($this->hasRole($role)) {
            throw new UserHasRoleException();
        }
        $this->roles[] = $role;
        return $this;
    }

    /**
     * Remove a role.
     *
     * @param string $role The role to remove.
     *
     * @throws UserHasRoleException if the user doesn't have the specified role.
     *
     * @return $this
     */
    public function removeRole(string $role)
    {
        if (!$this->hasRole($role)) {
            throw new UserHasNoRoleException();
        }
        unset($this->roles[array_search($role, $this->roles)]);
        return $this;
    }

    /**
     * Checks if a user has the specified role.
     *
     * @param  string $role The role to check.
     *
     * @return boolean
     */
    public function hasRole(string $role)
    {
        return in_array($role, $this->roles);
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password The users password.
     *
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username The user's username.
     *
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get email
     *
     * @return string The user's email address.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email The user's email address.
     *
     * @return $this
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime The created timestamp.
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created.
     *
     * @param \DateTime|null $datetime The datetime timestamp to set.
     *
     * @return $this
     */
    public function setCreated(\DateTime $datetime = null)
    {
        if ($datetime === null) {
            $datetime = new \DateTime();
        }
        $this->created = $datetime;
        return $this;
    }

    /**
     * Get status
     *
     * @return int The user's status.
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param int The status to set.
     *
     * @return $this
     */
    public function setStatus($status)
    {
        if (!in_array($status, [
            self::USER_ACTIVE,
            self::USER_BLOCKED,
        ])) {
            throw new InvalidStatusException();
        }
        $this->status = $status;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        if ($user instanceof User
            && $user->getUsername() == $this->username) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return ($this->status === self::USER_ACTIVE);
    }

    public function __toString()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getMemberships()
    {
        return $this->memberships;
    }

    /**
     * {@inheritdoc}
     */
    public function addMembership(GroupMembershipInterface $membership)
    {
        if (!$membership instanceof ProductionMembership) {
            throw new MembershipTypeNotSupportedException();
        }

        if (!$this->memberships->contains($membership)) {
            $this->memberships->add($membership);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeMembership(GroupMembershipInterface $membership)
    {
        if ($this->memberships->contains($membership)) {
            $this->memberships->remove($membership);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMembership(GroupMembershipInterface $membership)
    {
        return $this->memberships->contains($membership);
    }
}
