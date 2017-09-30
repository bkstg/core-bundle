<?php

namespace Bkstg\CoreBundle\Entity;

use Bkstg\CoreBundle\Exception\InvalidVisibilityException;
use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;
use Bkstg\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;

class Production implements GroupInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 0;

    const VISIBILITY_PUBLIC = 1;
    const VISIBILITY_PRIVATE = 0;

    private $id;
    private $name;
    private $description;
    private $created;
    private $updated;
    private $slug;
    private $status;
    private $expiry;
    private $visibility;
    private $image;
    private $author;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     * @return
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get description
     * @return
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get status
     * @return
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     * @return $this
     */
    public function setStatus(int $status)
    {
        if (!in_array($status, [
            self::STATUS_ACTIVE,
            self::STATUS_CLOSED,
        ])) {
            throw new InvalidStatusException();
        }

        $this->status = $status;
        return $this;
    }

    /**
     * Return the active status.
     */
    public function isActive()
    {
        return ($this->status === self::STATUS_ACTIVE);
    }

    /**
     * Get expiry
     * @return
     */
    public function getExpiry()
    {
        return $this->expiry;
    }

    /**
     * Set expiry
     * @return $this
     */
    public function setExpiry(\DateTime $expiry = null)
    {
        $this->expiry = $expiry;
        return $this;
    }

    /**
     * Return whether or not this is expired.
     */
    public function isExpired()
    {
        return ($this->expiry !== null && $this->expiry < new \DateTime('now'));
    }

    /**
     * Get visibility
     * @return
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set visibility
     * @return $this
     */
    public function setVisibility(int $visibility)
    {
        if (!in_array($visibility, [
            self::VISIBILITY_PRIVATE,
            self::VISIBILITY_PUBLIC,
        ])) {
            throw new InvalidVisibilityException();
        }
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * Get created
     * @return
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     * @return $this
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get updated
     * @return
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set updated
     * @return $this
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get slug
     * @return
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     * @return $this
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function isEqualTo(GroupInterface $group)
    {
        return $this->id === $group->getId();
    }

    /**
     * Set image
     *
     * @param \Bkstg\MediaBundle\Enity\Media $image
     *
     * @return Production
     */
    public function setImage(Media $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Bkstg\MediaBundle\Enity\Media
     */
    public function getImage()
    {
        return $this->image;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Production
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
