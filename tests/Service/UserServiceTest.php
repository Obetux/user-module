<?php
/**
 * Created by PhpStorm.
 * User: Claudio
 * Date: 03/12/2017
 * Time: 23:49
 */
namespace App\Test\Service;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Model\UserManager;
use App\Service\UserService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Cache\Simple\AbstractCache;

class UserServiceTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function cacheMock()
    {
        $cache = $this->createMock(AbstractCache::class);

        $cache->method('has')->willReturn(false);
        $cache->method('get')->willReturn(null);
        $cache->method('set')->willReturn(null);
        $cache->method('delete')->willReturn(null);

        return $cache;
    }

    /**
     * @throws UserNotFoundException
     */
    public function testCheck()
    {
        $user = new User();
        $user->setUsername('sarlanga');

        $userRepository = $this->createMock(EntityRepository::class);

        $userRepository->expects($this->any())
//                           ->method('findOneByUsername')
                            ->method('__call')
                            ->with('findOneByUsername')
                           ->willReturn($user);

        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->any())
                      ->method('getRepository')
                      ->willReturn($userRepository);


        $userService = new UserService($objectManager, $this->cacheMock());

        $this->assertEquals(true, $userService->check('sarlanga'));
    }

    /**
     *
     */
    public function testCheckUserNotFound()
    {
        $userRepository = $this->createMock(EntityRepository::class);

        $userRepository->expects($this->any())
//                           ->method('findOneByUsername')
            ->method('__call')
            ->with('findOneByUsername')
            ->willReturn(null);

        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userService = new UserService($objectManager, $this->cacheMock());

        $exep = null;
        try {
            $userService->check('XXX');
        } catch (\Exception $exep) {
        }

        $this->assertInstanceOf(UserNotFoundException::class, $exep);
    }


    public function testLogin()
    {
        $user = new User();
        $user->setUsername('sarlanga');
        $user->setPassword(UserManager::hashPassword('qubit'));


        $userRepository = $this->createMock(EntityRepository::class);

        $userRepository->expects($this->any())
//                           ->method('findOneByUsername')
                       ->method('__call')
                       ->with('findOneByUsername')
                       ->willReturn($user);


        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->any())
                      ->method('getRepository')
                      ->willReturn($userRepository);

        $userService = new UserService($objectManager, $this->cacheMock());

        $this->assertInstanceOf(\App\Model\User::class, $userService->login('sarlanga', 'qubit'));
    }


    public function testLoginUserNotFound()
    {

        $userRepository = $this->createMock(EntityRepository::class);

        $userRepository->expects($this->any())
//                           ->method('findOneByUsername')
                       ->method('__call')
                       ->with('findOneByUsername')
                       ->willReturn(null);

        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->any())
                      ->method('getRepository')
                      ->willReturn($userRepository);

        $userService = new UserService($objectManager, $this->cacheMock());

        $exep = null;
        try {
            $userService->login('XXX', 'sarlanga');
        } catch (\Exception $exep) {
        }

        $this->assertInstanceOf(UserNotFoundException::class, $exep);
    }

    public function testAutologin()
    {
        $user = new User();
        $user->setId(8);
        $user->setUsername('sarlanga');
        $user->setPassword('sarlanga');

        $userModule = new \App\Model\User();
        $userModule->hidrate($user);
        $hash = $userModule->getHash();

        $userRepository = $this->createMock(EntityRepository::class);

        $userRepository->expects($this->any())
//                           ->method('findOneByUsername')
            ->method('__call')
            ->with('findOneByUsername')
            ->willReturn($user);

        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userService = new UserService($objectManager, $this->cacheMock());

        $this->assertInstanceOf(\App\Model\User::class, $userService->autoLogin($hash));
    }


    public function testAutologinUserNotFound()
    {

        $userRepository = $this->createMock(EntityRepository::class);

        $userRepository->expects($this->any())
//                           ->method('findOneByUsername')
            ->method('__call')
            ->with('findOneByUsername')
            ->willReturn(null);

        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userService = new UserService($objectManager, $this->cacheMock());

        $exep = null;
        try {
            $userService->autoLogin('XXX');
        } catch (\Exception $exep) {
        }

        $this->assertInstanceOf(UserNotFoundException::class, $exep);
    }

    public function testGet()
    {
        $user = new User();
        $user->setUsername('sarlanga');
        $user->setPassword('sarlanga');

        $userRepository = $this->createMock(EntityRepository::class);

        $userRepository->expects($this->any())
//                           ->method('findOneByUsername')
            ->method('__call')
            ->with('findOneById')
            ->willReturn($user);

        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userService = new UserService($objectManager, $this->cacheMock());

        $this->assertInstanceOf(\App\Model\User::class, $userService->get('sarlanga'));
    }


    public function testGetUserNotFound()
    {

        $userRepository = $this->createMock(EntityRepository::class);

        $userRepository->expects($this->any())
//                           ->method('findOneByUsername')
            ->method('__call')
            ->with('findOneById')
            ->willReturn(null);

        $objectManager = $this->createMock(ObjectManager::class);

        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userService = new UserService($objectManager, $this->cacheMock());

        $exep = null;
        try {
            $userService->get('XXX');
        } catch (\Exception $exep) {
        }

        $this->assertInstanceOf(UserNotFoundException::class, $exep);
    }
}