<?php

namespace Bkstg\CoreBundle\Model\Group;

use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * Represents an entity that can be a group.
 *
 * Contains no ways to access memberships and groupable entities, this is to
 * maintain a one-way relationship between groups and their grouped members and
 * entities.  A group is simply a group, entities and memberships know their
 * group, a group does not necessarily know their entities and memberships.
 */
interface GroupInterface
{
    public function isEqualTo(GroupInterface $group);
}
