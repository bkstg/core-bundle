<?php

namespace Bkstg\CoreBundle\Model;

use Bkstg\CoreBundle\Model\Group\GroupMemberInterface;
use Bkstg\CoreBundle\Model\Group\GroupMembershipInterface;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;

class User extends BaseUser implements GroupMemberInterface
{

    protected $id;
    private $memberships;

    /**
     * Create a new instance of User.
     */
    public function __construct()
    {
        parent::__construct();
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
}
