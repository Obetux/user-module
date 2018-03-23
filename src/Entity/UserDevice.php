<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserDeviceRepository")
 *
 * @ORM\Table(
 *     indexes={
 *          @ORM\Index(name="user", columns={"user_id"}),
 *          @ORM\Index(name="user_userAgent", columns={"user_id", "user_agent"})
 *     })
 */
class UserDevice
{
    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $deviceId;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    protected $userId;

    /**
     * @ORM\Column(type="string",  nullable=true)
     */
    protected $userAgent;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @var \DateTime $lastLogin
     *
     * @ORM\Column(type="datetime",  nullable=true)
     */
    protected $lastLogin;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * UserDevice constructor.
     */
    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
    }


    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getDeviceId(): \Ramsey\Uuid\Uuid
    {
        return $this->deviceId;
    }

    /**
     * @param \Ramsey\Uuid\Uuid $deviceId
     *
     * @return UserDevice
     */
    public function setDeviceId(\Ramsey\Uuid\Uuid $deviceId): UserDevice
    {
        $this->deviceId = $deviceId;

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
     *
     * @return UserDevice
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param mixed $userAgent
     *
     * @return UserDevice
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     *
     * @return UserDevice
     */
    public function setActive($active)
    {
        $this->active = $active;

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
     *
     * @return UserDevice
     */
    public function setCreated(\DateTime $created): UserDevice
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     *
     * @return UserDevice
     */
    public function setUpdated(\DateTime $updated): UserDevice
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime $lastLogin
     *
     * @return UserDevice
     */
    public function setLastLogin(\DateTime $lastLogin): UserDevice
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }



}
