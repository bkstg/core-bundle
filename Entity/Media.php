<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Entity;

use Bkstg\CoreBundle\Model\ActiveInterface;
use Doctrine\Common\Collections\ArrayCollection;
use MidnightLuke\GroupSecurityBundle\Model\GroupableInterface;
use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;
use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;

class Media extends BaseMedia implements GroupableInterface, ActiveInterface
{
    protected $id;
    private $active;
    private $created;
    private $updated;
    private $groups;

    /**
     * Create a new media.
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int $id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set active.
     *
     * @param bool $active The active state.
     *
     * @return self
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return true === $this->active;
    }

    /**
     * Set created.
     *
     * @param \DateTimeInterface $created The created time.
     *
     * @return self
     */
    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTimeInterface
     */
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param \DateTimeInterface $updated The updated time.
     *
     * @return self
     */
    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTimeInterface
     */
    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    /**
     * {@inheritdoc}
     *
     * @return GroupInterface[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupInterface $group The group to add.
     *
     * @throws \Exception If the group is not a production.
     *
     * @return self
     */
    public function addGroup(GroupInterface $group): self
    {
        if (!$group instanceof Production) {
            throw new \Exception(sprintf('The group type "%s" is not supported.', get_class($group)));
        }
        $this->groups->add($group);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupInterface $group The group to remove.
     *
     * @return self
     */
    public function removeGroup(GroupInterface $group): self
    {
        $this->groups->remove($group);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupInterface $group The group to check for.
     *
     * @return bool
     */
    public function hasGroup(GroupInterface $group): bool
    {
        return $this->groups->contains($group);
    }
}
