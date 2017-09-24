<?php

namespace Bkstg\CoreBundle\Entity;

use Bkstg\CoreBundle\Entity\Group\GroupMemberInterface;
use Bkstg\CoreBundle\Entity\Group\GroupMembershipInterface;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;

class User extends BaseUser implements GroupMemberInterface
{

    protected $id;
    private $memberships;
    private $profiles;

    /**
     * Create a new instance of User.
     */
    public function __construct()
    {
        parent::__construct();
        $this->memberships = new ArrayCollection();
        $this->profiles = new ArrayCollection();
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

    public function __toString()
    {
        return $this->username;
    }

    /**
     * Add profile
     *
     * @param Profile $profile
     *
     * @return User
     */
    public function addProfile(Profile $profile)
    {
        $this->profiles[] = $profile;

        return $this;
    }

    /**
     * Remove profile
     *
     * @param Profile $profile
     */
    public function removeProfile(Profile $profile)
    {
        $this->profiles->removeElement($profile);
    }

    /**
     * Get profiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfiles()
    {
        return $this->profiles;
    }
}
