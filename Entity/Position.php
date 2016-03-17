<?php

namespace Bkstg\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Bkstg\CoreBundle\Entity\User;

/**
 * Position
 *
 * @ORM\Table(name="positions")
 * @ORM\Entity
 */
class Position
{

    const DESIGNATION_CREW = 'Crew';
    const DESIGNATION_CAST = 'Cast';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="designation", type="string")
     */
    private $designation;

    /**
     * @ORM\Column(name="role", type="string")
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="positions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;


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
     * Set designation
     *
     * @param string $designation
     * @return Position
     */
    public function setDesignation($designation)
    {
        if (!in_array($designation, array(self::DESIGNATION_CAST, self::DESIGNATION_CREW))) {
            throw new Exception('Invalid argument!');
        }
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Position
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set user
     *
     * @param \Bkstg\CoreBundle\Entity\User $user
     * @return Position
     */
    public function setUser(\Bkstg\CoreBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Bkstg\CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
