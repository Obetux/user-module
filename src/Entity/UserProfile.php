<?php
/**
 * Created by PhpStorm.
 * User: Claudio
 * Date: 16/10/2017
 * Time: 1:49
 */

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_profile")
 * @ORM\Entity()
 */
class UserProfile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * One Profile have One User.
     * @ORM\OneToOne(targetEntity="User", inversedBy="profile")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string $gender
     *
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected $gender;

    /**
     * One Product has One Shipment.
     * @ORM\OneToOne(targetEntity="ProfileImage")
     * @ORM\JoinColumn(name="avatar_id", referencedColumnName="id")
     */
    protected $avatar;

    /**
     * @var \DateTime $updated
     *
     * Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return UserProfile
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return UserProfile
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return UserProfile
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     * @return UserProfile
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }



   /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     * @return UserProfile
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }



}