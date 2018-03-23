<?php
/**
 * Created by PhpStorm.
 * User: Claudio
 * Date: 05/11/2017
 * Time: 23:46
 */

namespace App\Model;

use App\Entity\UserProfile as UserProfileEntity;

class UserProfile implements \JsonSerializable
{
    private $id;
    private $name;
    private $gender;
    private $image;
    private $restriction;
    private $type;

    /**
     * UserProfile constructor.
     */
    public function __construct()
    {
    }

    public function hydrate(UserProfileEntity $userProfile)
    {
        $this->id = $userProfile->getId();
        $this->name = $userProfile->getName();
        $this->gender = $userProfile->getGender();
        $this->image = $userProfile->getImage();
        $this->restriction = $userProfile->getRestriction();
        $this->type = $userProfile->getType();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'image' => $this->image,
            'restriction' => $this->restriction,
            'type' => $this->type,
        );
    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return UserProfile
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
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
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return UserProfile
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRestriction()
    {
        return $this->restriction;
    }

    /**
     * @param mixed $restriction
     * @return UserProfile
     */
    public function setRestriction($restriction)
    {
        $this->restriction = $restriction;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return UserProfile
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }




}