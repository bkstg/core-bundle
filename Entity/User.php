<?php

namespace Bkstg\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use FOS\UserBundle\Entity\User as BaseUser;
use Bkstg\CoreBundle\Entity\Position;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("email")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="first_name", type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string")
     */
    private $lastName;

    /**
     * @ORM\Column(name="phone_home", type="string", length=12, nullable=true)
     */
    private $phoneHome;

    /**
     * @ORM\Column(name="phone_mobile", type="string", length=12, nullable=true)
     */
    private $phoneMobile;

    /**
     * @ORM\OneToMany(targetEntity="Position", mappedBy="user", cascade={"persist"})
     */
    private $positions;

    /**
     * @ORM\Column(name="profile_image", type="string", nullable=true)
     */
    private $profileImage;

    /**
     * @ORM\Column(name="use_gravatar", type="boolean")
     */
    private $useGravatar;

    /**
     * @Assert\File(
     *     maxSize = "8M",
     *     mimeTypes = {
     *         "image/jpeg",
     *         "image/gif",
     *         "image/png"
     *     },
     *     maxSizeMessage = "The maxmimum allowed file size is 8MB.",
     *     mimeTypesMessage = "Filetype not allowed."
     * )
     */
    private $file;

    public function __toString()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->positions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phoneHome
     *
     * @param string $phoneHome
     * @return User
     */
    public function setPhoneHome($phoneHome)
    {
        $this->phoneHome = $phoneHome;

        return $this;
    }

    /**
     * Get phoneHome
     *
     * @return string
     */
    public function getPhoneHome()
    {
        return $this->phoneHome;
    }

    /**
     * Set phoneMobile
     *
     * @param string $phoneMobile
     * @return User
     */
    public function setPhoneMobile($phoneMobile)
    {
        $this->phoneMobile = $phoneMobile;

        return $this;
    }

    /**
     * Get phoneMobile
     *
     * @return string
     */
    public function getPhoneMobile()
    {
        return $this->phoneMobile;
    }

    /**
     * Add positions
     *
     * @param \Bkstg\CoreBundle\Entity\Position $positions
     * @return User
     */
    public function addPosition(\Bkstg\CoreBundle\Entity\Position $positions)
    {
        $this->positions[] = $positions;

        return $this;
    }

    /**
     * Remove positions
     *
     * @param \Bkstg\CoreBundle\Entity\Position $positions
     */
    public function removePosition(\Bkstg\CoreBundle\Entity\Position $positions)
    {
        $this->positions->removeElement($positions);
    }

    /**
     * Get positions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * Set profileImage
     *
     * @param string $profileImage
     * @return User
     */
    public function setProfileImage($profileImage)
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * Get profileImage
     *
     * @return string
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Called before saving the entity
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->profileImage = $filename . '.' . $this->file->guessExtension();
        }
    }

    /**
     * Called before entity removal
     *
     * @ORM\PreRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Called after entity persistence
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // The file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }

        // move takes the target directory and then the
        // target filename to move to
        $this->file->move(
            $this->getUploadRootDir(),
            $this->profileImage
        );

        // Clean up the file property as you won't need it anymore
        $this->file = null;
    }

    public function getProfileImageAbsolutePath()
    {
        return null === $this->profileImage
            ? null
            : $this->getUploadRootDir() . '/' . $this->profileImage;
    }

    public function getProfileImageWebPath()
    {
        return null === $this->profileImage
            ? null
            : $this->getUploadDir() . '/' . $this->profileImage;
    }

    public function getProfileImageUrl() {
        if ($this->usesGravatar()){
            return $this->getGravatarUrl();
        } else if ($this->getProfileImage()) {
            return '/' . $this->getProfileImageWebPath();
        } else {
            return $this->getGravatarUrl() . '&d=identicon&f=y';
        }
    }

    protected function getGravatarUrl() {
        return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->getEmail()))) . '?s=288';
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/profile_images';
    }

    /**
     * Utility function that generates a unique username.
     */
    public static function uniqueUserName(User $user, \Doctrine\ORM\EntityManager $em)
    {
        // create basic username and increment until it is unique
        $username = strtolower(substr($user->getFirstName(), 0, 1) . $user->getLastName());
        $append = 0;
        $new_username = $username;

        do {
            // check the username
            $user = $em
                ->getRepository('Bkstg\CoreBundle\Entity\User')
                ->findOneBy(array(
                    'username' => $new_username,
                ));

            // user found, create a new user
            if ($user !== null) {
                $new_username = $username . '_' . $append;
                $append++;
            }
        } while ($user !== null);

        // return the new unique username
        return $new_username;
    }

    /**
     * Set useGravatar
     *
     * @param boolean $useGravatar
     * @return User
     */
    public function setUseGravatar($useGravatar)
    {
        $this->useGravatar = $useGravatar;

        return $this;
    }

    /**
     * Get useGravatar
     *
     * @return boolean
     */
    public function getUseGravatar()
    {
        return $this->useGravatar;
    }

    /**
     * Get useGravatar
     *
     * @return boolean
     */
    public function usesGravatar()
    {
        return (boolean) $this->useGravatar;
    }
}
