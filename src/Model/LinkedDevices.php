<?php
/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 22/12/2017
 * Time: 16:57
 */

namespace App\Model;

class LinkedDevices implements \JsonSerializable
{
    private $devices = [];

    /**
     * VinculatedDevices constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getDevices(): array
    {
        return $this->devices;
    }

    /**
     * @param array $devices
     *
     * @return LinkedDevices
     */
    public function setDevices(array $devices): LinkedDevices
    {
        $this->devices = $devices;
        return $this;
    }

    /**
     * @param Device $device
     *
     * @return LinkedDevices
     */
    public function addDevice(Device $device)
    {
        $this->devices[] = $device;
        return $this;
    }

    public function hydrate(array $devices)
    {
        /* var App\Entity\UserDevice $link */
        foreach ($devices as $link) {
            $device = new Device();
            $device->hydrate($link);
            $this->addDevice($device);
        }
    }

    public function jsonSerialize()
    {
        $linked = array(
            'devices' => $this->devices,

        );
        return $linked;
    }
}
