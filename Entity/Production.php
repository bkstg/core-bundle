<?php

namespace Bkstg\CoreBundle\Entity;

use Bkstg\CoreBundle\Exception\InvalidVisibilityException;
use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;
use Bkstg\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;

class Production implements GroupInterface
{
    const VISIBILITY_PUBLIC = 'public';
    const VISIBILITY_PRIVATE = 'private';

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

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get name
     * @return
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get description
     * @return
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get status
     * @return
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * Set status
     * @return $this
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get expiry
     * @return
     */
    public function getExpiry(): ?\DateTimeInterface
    {
        return $this->expiry;
    }

    /**
     * Set expiry
     * @return $this
     */
    public function setExpiry(?\DateTimeInterface $expiry): self
    {
        $this->expiry = $expiry;
        return $this;
    }

    /**
     * Return whether or not this is expired.
     */
    public function isExpired(): bool
    {
        return ($this->expiry !== null && $this->expiry < new \DateTime('now'));
    }

    /**
     * Get visibility
     * @return
     */
    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    /**
     * Set visibility
     * @return $this
     */
    public function setVisibility(string $visibility): self
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * Get created
     * @return
     */
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    /**
     * Set created
     * @return $this
     */
    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get updated
     * @return
     */
    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    /**
     * Set updated
     * @return $this
     */
    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get slug
     * @return
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function isEqualTo(GroupInterface $group): bool
    {
        return (is_a($group, Production::class) && $this->id === $group->getId());
    }

    /**
     * Set image
     *
     * @param \Bkstg\MediaBundle\Enity\Media $image
     *
     * @return Production
     */
    public function setImage(?Media $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Bkstg\MediaBundle\Enity\Media
     */
    public function getImage(): ?Media
    {
        return $this->image;
    }

    public function __toString()
    {
        return $this->name ?: '';
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Production
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }
}
