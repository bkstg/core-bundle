<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\CoreBundle\Entity;

use Bkstg\MediaBundle\Entity\Media;
use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;

class Production implements GroupInterface
{
    private $id;
    private $name;
    private $description;
    private $created;
    private $updated;
    private $slug;
    private $status;
    private $expiry;
    private $image;
    private $author;

    /**
     * Get the id.
     *
     * @return ?integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the name.
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name.
     *
     * @param string $name The name.
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the description.
     *
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the description.
     *
     * @param string $description The description.
     *
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the status.
     *
     * @return ?boolean
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * Set the status.
     *
     * @param bool $status The status.
     *
     * @return self
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the expiry.
     *
     * @return ?\DateTimeInterface
     */
    public function getExpiry(): ?\DateTimeInterface
    {
        return $this->expiry;
    }

    /**
     * Set the expiry.
     *
     * @param ?\DateTimeInterface $expiry The expiry.
     *
     * @return self
     */
    public function setExpiry(?\DateTimeInterface $expiry): self
    {
        $this->expiry = $expiry;

        return $this;
    }

    /**
     * Return whether or not this is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return null !== $this->expiry && $this->expiry < new \DateTime('now');
    }

    /**
     * Get the created.
     *
     * @return ?\DateTimeInterface
     */
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    /**
     * Set the created.
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
     * Get the updated.
     *
     * @return ?\DateTimeInterface
     */
    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    /**
     * Set the updated.
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
     * Get the slug.
     *
     * @return ?string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set the slug.
     *
     * @param string $slug The slug.
     *
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupInterface $group The group to check against.
     *
     * @return bool
     */
    public function isEqualTo(GroupInterface $group): bool
    {
        return is_a($group, Production::class) && $this->id === $group->getId();
    }

    /**
     * Set the image.
     *
     * @param ?Media $image The image to set.
     *
     * @return self
     */
    public function setImage(?Media $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the image.
     *
     * @return ?Media
     */
    public function getImage(): ?Media
    {
        return $this->image;
    }

    /**
     * Set the author.
     *
     * @param string $author The author.
     *
     * @return self
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the author.
     *
     * @return ?string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Convert the production to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name ?: '';
    }
}
