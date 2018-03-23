<?php

namespace App\Service;

use App\Classes\Constant;
use Qubit\Bundle\UtilsBundle\Context\Context;
use App\Exception\BadCredentialsException;
use App\Exception\NewUserException;
use App\Exception\UpdateUserException;
use App\Exception\UserNotFoundException;
//use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use App\Model\UserManager;
use Doctrine\Common\Persistence\ObjectManager as Doctrine;
use App\Entity\User;
use Psr\SimpleCache\CacheInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Monolog\Logger;
use Qubit\Bundle\UtilsBundle\Utils\Util;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class UserService
 * @package App\Service
 */
class UserService
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
     * @var Stopwatch $stopwatch
     */
    private $stopwatch;

    /**
     * User constructor.
     *
     * @param Doctrine $doctrine
     * @param CacheInterface $cache
     * @param Logger $logger
     * @param Stopwatch $stopwatch
     */
    public function __construct(Doctrine $doctrine, CacheInterface $cache, Logger $logger, Stopwatch $stopwatch)
    {
        $this->doctrine = $doctrine;
        $this->cache = $cache;
        $this->logger = $logger;
        $this->stopwatch = $stopwatch;
    }

    /**
     * @return Doctrine
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param string $username
     * @return boolean
     * @throws UserNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function check($username)
    {
        $key = Util::sanitizeCacheKey('user.'.$username);
        /**  @var \App\Entity\User $user */
        $user = null;
        if (!$this->cache->has($key)) {
            $repository = $this->getDoctrine()->getRepository(User::class);
            $user = $repository->findOneByUsername($username);
            $this->cache->set($key, $user, 300);
        } else {
            $user = $this->cache->get($key);
        }

        if (!$user) {
            throw new UserNotFoundException();
        }

        return true;
    }

    /**
     * @param array $params
     * @return \App\Model\User
     * @throws UserNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get(array $params)
    {
        $this->stopwatch->start('UserService::get');
        $user = null;

        if (array_key_exists('userId', $params)) {
            $user = $this->getById($params['userId']);
        } elseif (array_key_exists('username', $params)) {
            $user = $this->getByUsername($params['username']);
        }

        if (!$user) {
            $this->logger->info('Get user not found');
            throw new UserNotFoundException();
        }

        $userModel = new \App\Model\User();
        $userModel->hydrate($user);

        $this->logger->info('Get User succeed', ['userId' => $user->getId()]);
        $this->stopwatch->stop('UserService::get');
        return $userModel;
    }

    /**
     * @param $userId
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getById($userId)
    {
        $this->stopwatch->start('UserService::getById');
        $this->logger->info('Get User', ['userId' => $userId]);

        $key = Util::sanitizeCacheKey('user.'.$userId);
        if (!$this->cache->has($key)) {
            $repository = $this->getDoctrine()->getRepository(User::class);
            $user = $repository->findOneById($userId);
            $this->cache->set($key, $user, Constant::CACHE_TTL_USER);
        } else {
            $user = $this->cache->get($key);
        }
        $this->stopwatch->stop('UserService::getById');
        return $user;
    }

    /**
     * @param $username
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getByUsername($username)
    {
        $this->stopwatch->start('UserService::getByUsername');
        $this->logger->info('Get User', ['username' => $username]);

        $key = Util::sanitizeCacheKey('user.'.$username);
        if (!$this->cache->has($key)) {
            $repository = $this->getDoctrine()->getRepository(User::class);
            $user = $repository->findOneByUsername($username);
            $this->cache->set($key, $user, Constant::CACHE_TTL_USER);
        } else {
            $user = $this->cache->get($key);
        }
        $this->stopwatch->stop('UserService::getByUsername');
        return $user;
    }

    /**
     * @param array $params
     *
     * @return \App\Model\User
     * @throws UserNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws NewUserException
     */
    public function new($params)
    {
        $this->logger->info('New User', ['username' => $params['username']]);
        $context = Context::getInstance();

        $user = new User();
        if (array_key_exists('firstName', $params)) {
            $user->setFirstName($params['firstName']);
        }
        if (array_key_exists('lastName', $params)) {
            $user->setLastName($params['lastName']);
        }
        if (array_key_exists('username', $params)) {
            $user->setUsername($params['username']);
        }
        if (array_key_exists('email', $params)) {
            $user->setEmail($params['email']);
        }
        if (array_key_exists('phone', $params)) {
            $user->setPhone($params['phone']);
        }
//        if (array_key_exists('password', $params)) {
//            $user->setPassword(UserManager::hashPassword($params['password']));
//        }
        $user->setAccount(Uuid::uuid4());
        $user->setRoles(['USER']);

        $user->setRegion($context->getRegion());
        $user->setCreated(new \DateTime());
        $user->setUpdated(new \DateTime());

        $manager = $this->getDoctrine();
        $manager->persist($user);
        try {
            $manager->flush();
        } catch (\Exception $exception) {
            $this->logger->error(
                'New User error',
                ['username' => $params['username'], 'error' => $exception->getMessage()]
            );
            throw new NewUserException();
        }

        $this->logger->info('New User succeed', ['username' => $params['username']]);

        return $this->get(['userId' => $user->getId()]);
    }

    /**
     * @param $userId
     * @param array $params
     *
     * @return \App\Model\User
     * @throws UserNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws UpdateUserException
     */
    public function modify($userId, $params)
    {
        $this->logger->info('New User', ['username' => $params['username']]);

        $repository = $this->getDoctrine()->getRepository(User::class);
        /**  @var \App\Entity\User $user */
        $user = $repository->findOneById($userId);

        if (!$user) {
            throw new UserNotFoundException();
        }

//        if (array_key_exists('firstName', $params) && !empty($params['firstName'])) {
//            $user->setFirstName($params['firstName']);
//        }
//        if (array_key_exists('lastName', $params) && !empty($params['lastName'])) {
//            $user->setLastName($params['lastName']);
//        }
//        if (array_key_exists('username', $params) && !empty($params['username'])) {
//            $user->setUsername($params['username']);
//        }
//        if (array_key_exists('email', $params) && !empty($params['email'])) {
//            $user->setEmail($params['email']);
//        }
//        if (array_key_exists('phone', $params) && !empty($params['phone'])) {
//            $user->setPhone($params['phone']);
//        }

        if ($val = $this->existAndNotEmpty('firstName', $params)) {
            $user->setFirstname($val);
        }
        if ($val = $this->existAndNotEmpty('lastName', $params)) {
            $user->setLastname($val);
        }
        if ($val = $this->existAndNotEmpty('username', $params)) {
            $user->setUsername($val);
        }
        if ($val = $this->existAndNotEmpty('email', $params)) {
            $user->setEmail($val);
        }
        if ($val = $this->existAndNotEmpty('phone', $params)) {
            $user->setPhone($val);
        }

        $user->setUpdated(new \DateTime());

        $manager = $this->getDoctrine();
        $manager->persist($user);
        try {
            $manager->flush();
        } catch (\Exception $exception) {
            $this->logger->error(
                'Update User error',
                ['username' => $params['username'], 'error' => $exception->getMessage()]
            );
            throw new UpdateUserException();
        }

        $this->logger->info('Update User succeed', ['username' => $user->getUsername()]);

        // Actualizo la cache del usuario
        $this->cache->setMultiple([
            Util::sanitizeCacheKey('user.'.$user->getId()) => $user,
            Util::sanitizeCacheKey('user.'.$user->getUsername()) => $user,
        ], Constant::CACHE_TTL_USER);

        return $this->get(['userId' => $user->getId()]);
    }

    private function existAndNotEmpty($key, $array)
    {
        if (array_key_exists($key, $array) && !empty($array[$key])) {
            return $array[$key];
        }
        return null;
    }


}