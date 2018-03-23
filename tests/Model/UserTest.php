<?php

namespace App\Tests\Model;

use App\Model\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 * @package App\Tests\Model
 */
class UserTest extends TestCase
{

    /**
     *
     */
    public function testId()
    {
        $var = 1;
        $user = new User();
        $user->setId($var);
        $this->assertEquals($var, $user->getId());
    }

    /**
     *
     */
    public function testUsername()
    {
        $var = 'username';
        $user = new User();
        $user->setUsername($var);
        $this->assertEquals($var, $user->getUsername());
    }

    /**
     *
     */
    public function testFirstname()
    {
        $var = 'firstname';
        $user = new User();
        $user->setFirstname($var);
        $this->assertEquals($var, $user->getFirstname());
    }

    /**
     *
     */
    public function testLastname()
    {
        $var = 'Lastname';
        $user = new User();
        $user->setLastname($var);
        $this->assertEquals($var, $user->getLastname());
    }

    /**
     *
     */
    public function testEmail()
    {
        $var = 'email@email.com';
        $user = new User();
        $user->setEmail($var);
        $this->assertEquals($var, $user->getEmail());
    }

    /**
     *
     */
    public function testLastLogin()
    {
        $var = new \DateTime();
        $user = new User();
        $user->setLastLogin($var);
        $this->assertEquals($var, $user->getLastLogin());
    }

    /**
     *
     */
    public function testPhone()
    {
        $var = '455434545';
        $user = new User();
        $user->setPhone($var);
        $this->assertEquals($var, $user->getPhone());
    }

    /**
     *
     */
    public function testRegion()
    {
        $var = 'AR';
        $user = new User();
        $user->setRegion($var);
        $this->assertEquals($var, $user->getRegion());
    }

    /**
     *
     */
    public function testHash()
    {
        $var = 'afsdfsdfgrgfgdfg';
        $user = new User();
        $user->setHash($var);
        $this->assertEquals($var, $user->getHash());
    }

    /**
     *
     */
    public function testRequireNewPassword()
    {
        $var = true;
        $user = new User();
        $user->setRequireNewPassword($var);
        $this->assertEquals($var, $user->getRequireNewPassword());
    }

    /**
     *
     */
    public function testValidatedUser()
    {
        $var = true;
        $user = new User();
        $user->setValidatedUser($var);
        $this->assertEquals($var, $user->getValidatedUser());
    }

    /**
     *
     */
    public function testProfiles()
    {
        $var = new ArrayCollection();
        $user = new User();
        $user->setProfiles($var);
        $this->assertInstanceOf(ArrayCollection::class, $user->getValidatedUser());
    }
}
