<?php

namespace App\Service;

use App\Classes\Constant;
use Qubit\Bundle\UtilsBundle\Context\Context;
use App\Entity\LoginHistory;
use App\Entity\UserDevice;
use App\Exception\BadCredentialsException;
use App\Exception\BruteForceException;
use App\Exception\DeviceNotEnableException;
use App\Exception\LinkedDeviceNotFoundException;
use App\Exception\NewUserException;
use App\Exception\UpdateUserException;
use App\Exception\UserNotFoundException;
//use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use App\Model\LoginHistoryCollection;
use App\Model\UserManager;
use App\Model\LinkedDevices;
use Doctrine\Common\Persistence\ObjectManager as Doctrine;
use App\Entity\User;
use Psr\SimpleCache\CacheInterface;
use Symfony\Bridge\Monolog\Logger;
use Qubit\Bundle\UtilsBundle\Utils\Util;

/**
 * Class UserService
 * @package App\Service
 */
class AuthExtraService
{

    /**
     * @var Doctrine
     */
    private $doctrine;
    /**
     * @var CacheInterface $cache
     */
    private $cache;

    /**
     * @var Logger $logger
     */
    private $logger;

    /**
     * User constructor.
     *
     * @param Doctrine $doctrine
     * @param CacheInterface $cache
     * @param Logger $logger
     */
    public function __construct(Doctrine $doctrine, CacheInterface $cache, Logger $logger)
    {
        $this->doctrine = $doctrine;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @return Doctrine
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param $userId
     * @return \App\Model\LinkedDevices
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getLinkedDevices($userId)
    {
        $this->logger->info('Get Linked Devices', ['userId' => $userId]);

        // TODO PAGINAR
        $repository = $this->getDoctrine()->getRepository(UserDevice::class);
        $devices = $repository->findBy(
            [   'userId' => $userId,
//                'active' => true
            ],
            ['updated' => 'DESC']
        );
//        dump($devices);exit;
        $linkedDevices = new LinkedDevices();
        $linkedDevices->hydrate($devices);

        $this->logger->info('Get Linked Devices succeed', ['userId' => $userId]);

        return $linkedDevices;
    }

    /**
     * @param $userId
     * @param $deviceId
     *
     * @throws LinkedDeviceNotFoundException
     */
    public function disableDevice($userId, $deviceId)
    {
        $this->logger->info('Disable Device attempt', ['userId' => $userId, 'deviceId' => $deviceId]);

        $repository = $this->getDoctrine()->getRepository(UserDevice::class);
        /** var \App\Entity\LinkedDevice $device */
        $device = $repository->findOneBy(
            [   'userId' => $userId,
                'deviceId' => $deviceId
            ]
        );

        if (!$device) {
            $this->logger->info('Linked Device not found', ['userId' => $userId, 'deviceId' => $deviceId]);
            throw new LinkedDeviceNotFoundException();
        }

        $manager = $this->getDoctrine();
        $device->setActive(false);
        $manager->persist($device);
        $manager->flush();

        $this->logger->info('Disable Device succeed', ['userId' => $userId, 'deviceId' => $deviceId]);
    }

    public function getLoginHistory($userId)
    {
        $this->logger->info('Get Login History', ['userId' => $userId]);

        // TODO PAGINAR
        $repository = $this->getDoctrine()->getRepository(LoginHistory::class);
        $history = $repository->findBy(
            [   'userId' => $userId,
//                'active' => true
            ],
            ['created' => 'DESC']
        );

        $loginHistories = new LoginHistoryCollection();
        $loginHistories->hydrate($history);

        $this->logger->info('Get Login History succeed', ['userId' => $userId]);
        return $loginHistories;
    }


}