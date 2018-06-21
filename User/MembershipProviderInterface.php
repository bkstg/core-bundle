<?php

namespace Bkstg\CoreBundle\User;

use Bkstg\CoreBundle\Entity\Production;
use Bkstg\CoreBundle\User\ProductionMembershipInterface;
use Bkstg\CoreBundle\User\UserInterface;
use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;
use MidnightLuke\GroupSecurityBundle\Model\GroupMemberInterface;

interface MembershipProviderInterface
{
    /**
     * Should return the membership associated with the user and production.
     *
     * Does not account for the membership being active/inactive/expired, so
     * code relying on this method should check the membership that is returned.
     *
     * @param  Production    $production The production to get.
     * @param  UserInterface $user       The user to get.
     * @return ProductionMembershipInterface The membership.
     */
    public function loadMembership(Production $production, UserInterface $user): ?ProductionMembershipInterface;

    /**
     * Should return all active memberships for the given production.
     *
     * Active is defined as non-expired memberships with a status of "true".
     *
     * @param  Production $production The production to get.
     * @return ProductionMembershipInterface[] The memberships.
     */
    public function loadActiveMembershipsByProduction(Production $production);

    /**
     * Should return all active memberships for the given user.
     *
     * Active is defined as non-expired memberships with a status of "true".
     *
     * @param  UserInterface $user The user to get.
     * @return ProductionMembershipInterface[] The memberships.
     */
    public function loadActiveMembershipsByUser(UserInterface $user);

    /**
     * Should return all memberships for the given production.
     *
     * @param  Production $production The production to get.
     * @return ProductionMembershipInterface[] The memberships.
     */
    public function loadAllMembershipsByProduction(Production $production);

    /**
     * Should return all inactive memberships for the given user.
     *
     * @param  UserInterface $user The user to get.
     * @return ProductionMembershipInterface[] The memberships.
     */
    public function loadAllMembershipsByUser(UserInterface $user);
}
