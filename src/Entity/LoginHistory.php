<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LoginHistoryRepository")
 * @ORM\Table(
 *     indexes={
 *          @ORM\Index(name="user", columns={"user_id"})
  *     })
 */
class LoginHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    protected $userId;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    protected $deviceId;

//TODO Revisar formato de IP
    /**
     * @Assert\Ip
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    protected $ipAddress;

    /**
     * @var boolean $boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $state;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * LoginHistory constructor.
     */
    public function __construct()
    {
        $this->setCreated(new \DateTime());
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
     * @return LoginHistory
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     * @return LoginHistory
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param mixed $ipAddress
     * @return LoginHistory
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @return bool
     */
    public function isState(): bool
    {
        return $this->state;
    }

    /**
     * @param bool $state
     * @return LoginHistory
     */
    public function setState(bool $state): LoginHistory
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return LoginHistory
     */
    public function setCreated(\DateTime $created): LoginHistory
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * @param mixed $deviceId
     * @return LoginHistory
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;
        return $this;
    }

}
