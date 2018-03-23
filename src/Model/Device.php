<?php
/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 22/12/2017
 * Time: 16:58
 */

namespace App\Model;


use App\Entity\UserDevice;
use Qubit\Bundle\UtilsBundle\Utils\BrowserDetector;

class Device implements \JsonSerializable
{
    private $deviceId;
    private $userId;
    private $userAgent;
    private $active;
    private $created;
    private $updated;
    private $lastLogin;

    private $platform;
    private $platformVersion;
    private $browserName;
    private $majorVersion;
    private $version;
    private $isMobile;
    private $isTablet;


    public function jsonSerialize()
    {

        $user = array(
            'deviceId' => $this->deviceId,
            'userId' => $this->userId,
            'userAgent' => $this->userAgent,
            'active' => $this->active,
            'created' => $this->created,
            'updated' => $this->updated,
            'lastLogin' => $this->lastLogin,
            'platform' => $this->platform,
            'platformVersion' => $this->platformVersion,
            'browserName' => $this->browserName,
            'majorVersion' => $this->majorVersion,
            'version' => $this->version,
            'isMobile' => $this->isMobile,
            'isTablet' => $this->isTablet,

        );

        return $user;
    }


    /**
     * @param UserDevice $device
     */
    public function hydrate(UserDevice $device)
    {
        $this->setDeviceId($device->getDeviceId());
        $this->setUserId($device->getUserId());
        $this->setUserAgent($device->getUserAgent());
        $this->setActive($device->isActive());
        $this->setCreated($device->getCreated());
        $this->setUpdated($device->getUpdated());
        $this->setLastLogin($device->getLastLogin());

        $browser = new BrowserDetector($this->getUserAgent());
        $this->setPlatform($browser->getPlatform());
        $this->setPlatformVersion($browser->getPlatformVersion());
        $this->setBrowserName($browser->getBrowser());
        $this->setMajorVersion($browser->getMajorVersion());
        $this->setVersion($browser->getVersion());
        $this->setIsMobile($browser->isMobile());
        $this->setIsTablet($browser->isTablet());
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
     *
     * @return Device
     */
    public function setDeviceId($deviceId)
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
     * @return Device
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
     * @return Device
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
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
     * @return Device
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     *
     * @return Device
     */
    public function setCreated($created)
    {
        $this->created = $created;

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
     *
     * @return Device
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param mixed $lastLogin
     *
     * @return Device
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @param mixed $platform
     *
     * @return Device
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlatformVersion()
    {
        return $this->platformVersion;
    }

    /**
     * @param mixed $platformVersion
     *
     * @return Device
     */
    public function setPlatformVersion($platformVersion)
    {
        $this->platformVersion = $platformVersion;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrowserName()
    {
        return $this->browserName;
    }

    /**
     * @param mixed $browserName
     *
     * @return Device
     */
    public function setBrowserName($browserName)
    {
        $this->browserName = $browserName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMajorVersion()
    {
        return $this->majorVersion;
    }

    /**
     * @param mixed $majorVersion
     *
     * @return Device
     */
    public function setMajorVersion($majorVersion)
    {
        $this->majorVersion = $majorVersion;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     *
     * @return Device
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isMobile()
    {
        return $this->isMobile;
    }

    /**
     * @param mixed $isMobile
     *
     * @return Device
     */
    public function setIsMobile($isMobile)
    {
        $this->isMobile = $isMobile;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isTablet()
    {
        return $this->isTablet;
    }

    /**
     * @param mixed $isTablet
     *
     * @return Device
     */
    public function setIsTablet($isTablet)
    {
        $this->isTablet = $isTablet;

        return $this;
    }

}