<?php
/**
 * Created by PhpStorm.
 * User: Claudio
 * Date: 26/12/2017
 * Time: 0:49
 */

namespace App\Model;

class LoginHistory implements \JsonSerializable
{
    private $userId;
    private $deviceId;
    private $ipAddress;
    private $state;
    private $created;


    public function jsonSerialize()
    {
        $history = array(
            'userId' => $this->userId,
            'deviceId' => $this->deviceId,
            'ipAddress' => $this->ipAddress,
            'state' => $this->state,
            'created' => $this->created,

        );
        return $history;
    }

    public function hydrate(\App\Entity\LoginHistory $history)
    {
        $this->setUserId($history->getUserId());
        // TODO Devolver ingo del device
        $this->setDeviceId($history->getDeviceId());
        $this->setIpAddress($history->getIpAddress());
        $this->setState($history->isState());
        $this->setCreated($history->getCreated());
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
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * @param mixed $deviceId
     *
     * @return LoginHistory
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;

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
     *
     * @return LoginHistory
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getState(){
        return $this->state;
    }

    /**
     * @param mixed $state
     *
     * @return LoginHistory
     */
    public function setState($state)
    {
        $this->state = $state;

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
     * @return LoginHistory
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }


}