<?php

namespace App\Service;

use App\Classes\Constant;
use Qubit\Bundle\UtilsBundle\Context\Context;
use App\Entity\Auth;
use App\Entity\LoginHistory;
use App\Entity\UserDevice;
use App\Exception\BadCredentialsException;
use App\Exception\BruteForceException;
use App\Exception\DeviceNotEnableException;
use App\Exception\UserNotFoundException;
//use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use App\Model\UserManager;
use Doctrine\Common\Persistence\ObjectManager as Doctrine;
use Psr\SimpleCache\CacheInterface;
use Qubit\Bundle\UtilsBundle\Utils\Util;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class AuthService
 * @package App\Service
 */
class AuthService
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
     * @param string $userId
     * @param string $password
     * @return \App\Model\User
     * @throws UserNotFoundException
     * @throws BadCredentialsException
     * @throws BruteForceException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function login($userId, $password)
    {
        $this->logger->info('Login attemp', ['userId' => $userId]);

        $context = Context::getInstance();

        $this->logger->debug('Start check brute force');
        if ($this->checkBruteForceLogin()) {
            $this->logger->warning(
                'Login brute force',
                [ 'userId' => $userId,
                  'ipAddress' => $context->getIpAddress()
                ]
            );
            throw new BruteForceException();
        }
        $this->logger->debug('End check brute force');

        $key = Util::sanitizeCacheKey('auth.'.$userId);
        /**  @var \App\Entity\User $user */
        $auth = null;
        if (!$this->cache->has($key)) {
            $this->logger->debug('User not in cache. Search in DB');
            $repository = $this->getDoctrine()->getRepository(Auth::class);
            /**  @var \App\Entity\User $auth */
            $auth = $repository->findOneByUserId($userId);
            $this->logger->debug('End Search user query');
        } else {
            $this->logger->debug('User find in cache');
            $auth = $this->cache->get($key);
            $this->logger->debug('End search user cache');
        }

        if (!$auth) {
            $this->logger->debug('User not found');
            $this->updateLoginHistory(null, false);
            $this->logger->info('Login user not found', ['userId' => $userId]);
            throw new UserNotFoundException();
        }
        $this->logger->debug('User found. Start validate Password');
        if (!UserManager::isPasswordValid($auth->getPassword(), $password, $auth->getSalt())) {
            $this->logger->debug('Password not valid');
            $this->updateLoginHistory($auth, false);
            $this->logger->info('Login bad credentials', ['userId' => $userId]);
            throw new BadCredentialsException();
        }

        $this->logger->debug('Start Check first Login');
        $this->checkFirstLogin($auth);
        $this->logger->debug('End Check first Login');

        $this->updateLoginHistory($auth, true);

//        $this->logger->debug('Start Hydrate User Model');
//        $userModel = new \App\Model\User();
//        $userModel->hydrate($user);
//        $this->logger->debug('End Hydrate User Model');

        // Actualizo la cache del usuario
        $this->logger->debug('Start Persist Auth in cache');
        $this->cache->setMultiple([
            Util::sanitizeCacheKey('auth.'.$auth->getUserId()) => $auth,
        ], Constant::CACHE_TTL_USER);
        $this->logger->debug('End Persist Auth in cache');

        $this->logger->info('Login succeed', ['userId' => $userId]);

        return $auth->getUserId();
    }

    /**
     * @param string $hash
     *
     * @return \App\Model\User
     * @throws UserNotFoundException
     * @throws BruteForceException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws DeviceNotEnableException
     */
    public function autoLogin($hash)
    {
        $this->logger->info('AutoLogin attemp');

        $context = Context::getInstance();

        $this->logger->debug('Start check brute force');
        if ($this->checkBruteForceLogin()) {
            $this->logger->warning(
                'AutoLogin brute force',
                ['ipAddress' => $context->getIpAddress()]
            );
            throw new BruteForceException();
        }
        $this->logger->debug('End check brute force');

        $this->logger->debug('Start decrypt User Hash');
        $decoded = \App\Model\User::decryptUserHash($hash);
        $this->logger->debug('End decrypt User Hash');
        $repository = $this->getDoctrine()->getRepository(Auth::class);
        $auth = null;
        $deviceId = null;
        if (!is_null($decoded)) {
            if (property_exists($decoded, 'id')) {
                $key = Util::sanitizeCacheKey('auth.'.$decoded->id);
                if (!$this->cache->has($key)) {
                    $this->logger->debug('User not in cache. Search in DB');
                    $auth = $repository->findOneById($decoded->id);
                    $this->logger->debug('End search user query');
                } else {
                    $this->logger->debug('User find in cache');
                    $auth = $this->cache->get($key);
                    $this->logger->debug('End search user cache');
                }
            }

            if (property_exists($decoded, 'deviceId')) {
                $deviceId = $decoded->deviceId;
            }
        }

        if (!$auth) {
            $this->logger->debug('User not found');
            $this->updateLoginHistory(null, false);
            $this->logger->info('AutoLogin user not found');
            throw new UserNotFoundException();
        }

        // Verifico si poseo el dispositivo registrado y habilitado
        $this->logger->debug('User found. Start validate Device');
        if (!$this->checkDevice($auth->getUserId(), $deviceId)) {
            $this->logger->info('AutoLogin user device not active');
            throw new DeviceNotEnableException();
        }

        $this->logger->debug('Start Check first Login');
        $this->checkFirstLogin($auth);
        $this->logger->debug('End Check first Login');

        $this->updateLoginHistory($auth, true);

//        $this->logger->debug('Start Hydrate User Model');
//        $userModel = new \App\Model\User();
//        $userModel->hydrate($user);
//        $this->logger->debug('End Hydrate User Model');

        // Actualizo la cache del usuario
        $this->logger->debug('Start Persist User in cache');
        $this->cache->setMultiple([
            Util::sanitizeCacheKey('auth.'.$auth->getUserId()) => $auth,
        ], Constant::CACHE_TTL_USER);
        $this->logger->debug('End Persist User in cache');

        $this->logger->info('AutoLogin succeed', ['userId' => $auth->getUserId()]);

        return $auth->getUserId();
    }

    /**
     * @param Auth $auth
     */
    private function checkFirstLogin(Auth $auth) : void
    {
        $lastLogin = $auth->getLastLogin();
        $today = new \DateTime();

        if ((is_null($lastLogin)) || ($lastLogin->format('Y-m-d') < $today->format('Y-m-d'))) {
            $this->logger->info('First Login of the day');
        }

        return;
    }
    /**
     * @return string
     */
    private function getBruteForceKey()
    {
        $context = Context::getInstance();
        return Util::sanitizeCacheKey('bruteforce.'.$context->getIpAddress());
    }

    /**
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function checkBruteForceLogin()
    {
        $key = $this->getBruteForceKey();
        $attempts = 0;
        if ($this->cache->has($key)) {
            $attempts = $this->cache->get($key);
        }

        if ($attempts >= 3) {
            $this->cache->set($key, $attempts + 1, Constant::CACHE_TTL_BRUTEFORCE);
            $this->updateLoginHistory(null, false);
            return true;
        }
        return false;
    }

    /**
     * @param Auth $auth
     * @param bool $state
     */
    private function updateLoginHistory(?Auth $auth, bool $state = true)
    {
        $context = Context::getInstance();
        $manager = $this->getDoctrine();

        $userId = ($auth) ? $auth->getUserId() : null;
        $this->logger->debug('Start register login history');
        $history = new LoginHistory();
        $history->setUserId($userId);
        $history->setIpAddress($context->getIpAddress());
        $history->setState($state);
        if ($context->getDeviceId()) {
            $history->setDeviceId($context->getDeviceId());
        }


        if ($state) {
            $this->persistDevice($userId);
            // Si se logueo correctamente, actualizo el lastlogin del usuario
            $auth->setLastLogin(new \DateTime());
            $manager->merge($auth);
        }

        $this->logger->debug('Start persist login history');
        $manager->persist($history);
        $manager->flush();
        $this->logger->debug('End persist login history');

        $this->logger->debug('Start update brute force attempt');
        $key = $this->getBruteForceKey();
        try {
            if ($state) {
                $this->cache->delete($key);
            } else {
                $attempts = 0;
                if ($this->cache->has($key)) {
                    $attempts = $this->cache->get($key);
                }
                $this->cache->set($key, $attempts + 1, Constant::CACHE_TTL_BRUTEFORCE);
            }
        } catch (\Psr\SimpleCache\InvalidArgumentException $exception) {
        }
        $this->logger->debug('End update brute force attempt');
    }

    /**
     * @param $userId
     */
    private function persistDevice($userId)
    {
        $context = Context::getInstance();
        $manager = $this->getDoctrine();
        $repository = $manager->getRepository(UserDevice::class);
        $device = null;
        // Si tengo el device en el contexto lo uso para buscarlo
        if ($context->getDeviceId()) {
            $this->logger->debug('Start search device by deviceId');
            $device = $repository->findOneBy(
                ['deviceId' => $context->getDeviceId()]
            );
            $this->logger->debug('End search device by deviceId');
        } else {
            // Caso contrario, busco por UserId y userAgent
            $this->logger->debug('Start search device by userId and UserAgent');
            $device = $repository->findOneBy(
                [   'userId' => $userId,
                    'userAgent' => $context->getUserAgent()
                ]
            );
            $this->logger->debug('End search device by userId and UserAgent');
        }

        if (!$device) {
            $this->logger->debug('Create new device');
            $device = new UserDevice();
        }
        $device->setUserId($userId);
        $device->setActive(true);
        $device->setUserAgent($context->getUserAgent());
        $device->setLastLogin(new \DateTime());

        $this->logger->debug('Start persist device');
        $manager->persist($device);
        $manager->flush();
        $this->logger->debug('End persist device');

        // Una vez persistido el Device, lo seteo al contexto
        $context->setDeviceId($device->getDeviceId());
    }

    /**
     * @param $userId
     * @param $deviceId
     *
     * @return bool
     */
    private function checkDevice($userId, $deviceId)
    {
        $manager = $this->getDoctrine();
        $repository = $manager->getRepository(UserDevice::class);
        $device = $repository->findOneBy(
            [   'userId' => $userId,
                'deviceId' => $deviceId,
                'active' => true
            ]
        );

        if (!$device) {
            return false;
        }
        return true;
    }

//    /**
//     * @param User $user
//     */
//    private function updateLastLogin(User $user)
//    {
//        $user->setLastLogin(new \DateTime());
//        $manager = $this->getDoctrine();
//        $manager->merge($user);
//        $manager->flush();
//
////        $manager->persist($user);
////        $manager->merge()
//    }

     /**
     * @param string $userId
     * @param string $currentPassword
     * @param string $newPassword
     *
     * @return bool
     * @throws UserNotFoundException
     * @throws BadCredentialsException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function changePassword($userId, $currentPassword, $newPassword)
    {
        // No puedo obtener el usuario del cache, debido a que Doctrine lo considera un nuevo registro
        $repository = $this->getDoctrine()->getRepository(Auth::class);
        /**  @var \App\Entity\User $user */
        $auth = $repository->findOneById($userId);

        if (!$auth) {
            throw new UserNotFoundException();
        }

        if (!UserManager::isPasswordValid($auth->getPassword(), $currentPassword, $auth->getSalt())) {
            throw new BadCredentialsException();
        }

        $auth->setSalt(null);
        $auth->setPassword(UserManager::hashPassword($newPassword));

        $manager = $this->getDoctrine();
        $manager->persist($auth);
        $manager->flush();

        // Actualizo la cache del usuario
         $this->cache->setMultiple([
            Util::sanitizeCacheKey('auth.'.$auth->getId()) => $auth,
         ], Constant::CACHE_TTL_USER);

         return true;
    }

    /**
     * @param $userId
     *
     * @return bool
     * @throws UserNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function resetPassword($userId)
    {
        // TODO Definir donde dejar la password por defecto para resetear, o cual es la logica
        $defaultPassword = 'QUBIT';
        // No puedo obtener el usuario del cache, debido a que Doctrine lo considera un nuevo registro
        $repository = $this->getDoctrine()->getRepository(Auth::class);
        /**  @var \App\Entity\Auth $auth */
        $auth = $repository->findOneById($userId);

        if (!$auth) {
            throw new UserNotFoundException();
        }

        $auth->setSalt(null);
        $auth->setPassword(UserManager::hashPassword($defaultPassword));
// TODO enviar evento para notificar por mail o lo que sea la nueva password

        $manager = $this->getDoctrine();
        $manager->persist($auth);
        $manager->flush();

        // Actualizo la cache del usuario
        $this->cache->setMultiple([
            Util::sanitizeCacheKey('auth.'.$auth->getId()) => $auth,
        ], Constant::CACHE_TTL_USER);

        return true;
    }

    /**
     * @param string $userId
     * @param string $password
     * @return bool
     */
    public function new(string $userId, string $password)
    {
        $auth = new Auth();
        $auth->setUserId($userId);
        $auth->setPassword(UserManager::hashPassword($password));
        $auth->setSalt(null);
        $auth->setCreated(new \DateTime());
        $auth->setUpdated(new \DateTime());
        $manager = $this->getDoctrine();
        $manager->persist($auth);
        $manager->flush();

        return true;
    }

}