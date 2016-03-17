<?php

namespace Bkstg\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Message
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity
 */
class Message
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="glyph", type="string", length=25, nullable=true)
     */
    private $glyph;

    /**
     * @var string
     *
     * @ORM\Column(name="link_path", type="string", nullable=true)
     */
    private $linkPath;

    /**
     * @var string
     *
     * @ORM\Column(name="link_args", type="json_array", nullable=true)
     */
    private $linkArgs;

    /**
     * @ORM\ManyToMany(targetEntity="\Bkstg\CoreBundle\Entity\User")
     * @ORM\JoinTable(name="users_messages",
     *     joinColumns={@ORM\JoinColumn(name="message_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $seenBy;

    /**
     * @ORM\Column(name="referenced_entity_type", type="string", length=128, nullable=true)
     */
    private $referencedEntityType;

    /**
     * @ORM\Column(name="referenced_entity_id", type="integer", nullable=true)
     */
    private $referencedEntityId;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seenBy = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Message
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Message
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set linkPath
     *
     * @param string $linkPath
     * @return Message
     */
    public function setLinkPath($linkPath)
    {
        $this->linkPath = $linkPath;

        return $this;
    }

    /**
     * Get linkPath
     *
     * @return string
     */
    public function getLinkPath()
    {
        return $this->linkPath;
    }

    /**
     * Set linkArgs
     *
     * @param array $linkArgs
     * @return Message
     */
    public function setLinkArgs($linkArgs)
    {
        $this->linkArgs = $linkArgs;

        return $this;
    }

    /**
     * Get linkArgs
     *
     * @return array
     */
    public function getLinkArgs()
    {
        return $this->linkArgs;
    }

    /**
     * Add seenBy
     *
     * @param \Bkstg\CoreBundle\Entity\User $seenBy
     * @return Message
     */
    public function addSeenBy(\Bkstg\CoreBundle\Entity\User $seenBy)
    {
        $this->seenBy[] = $seenBy;

        return $this;
    }

    /**
     * Remove seenBy
     *
     * @param \Bkstg\CoreBundle\Entity\User $seenBy
     */
    public function removeSeenBy(\Bkstg\CoreBundle\Entity\User $seenBy)
    {
        $this->seenBy->removeElement($seenBy);
    }

    /**
     * Get seenBy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeenBy()
    {
        return $this->seenBy;
    }

    /**
     * Determines if a message has been seen by a given user.
     */
    public function seenBy(\Bkstg\CoreBundle\Entity\User $user) {
        return $this->seenBy->contains($user);
    }

    /**
     * Set glyph
     *
     * @param string $glyph
     * @return Message
     */
    public function setGlyph($glyph)
    {
        $this->glyph = $glyph;

        return $this;
    }

    /**
     * Get glyph
     *
     * @return string
     */
    public function getGlyph()
    {
        return $this->glyph;
    }

    /**
     * Set referencedEntityType
     *
     * @param string $referencedEntityType
     * @return Message
     */
    public function setReferencedEntityType($referencedEntityType)
    {
        $this->referencedEntityType = $referencedEntityType;

        return $this;
    }

    /**
     * Get referencedEntityType
     *
     * @return string
     */
    public function getReferencedEntityType()
    {
        return $this->referencedEntityType;
    }

    /**
     * Set referencedEntityId
     *
     * @param integer $referencedEntityId
     * @return Message
     */
    public function setReferencedEntityId($referencedEntityId)
    {
        $this->referencedEntityId = $referencedEntityId;

        return $this;
    }

    /**
     * Get referencedEntityId
     *
     * @return integer
     */
    public function getReferencedEntityId()
    {
        return $this->referencedEntityId;
    }
}
