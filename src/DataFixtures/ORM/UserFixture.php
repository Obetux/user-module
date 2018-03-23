<?php
/**
 * Created by PhpStorm.
 * User: Claudio
 * Date: 16/10/2017
 * Time: 22:36
 */

namespace App\DataFixtures\ORM;

use App\Classes\Constant;
use App\Entity\Auth;
use App\Entity\User;
use App\Entity\UserPreferences;
use App\Entity\UserProfile;
use App\Model\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\ORMException;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

class UserFixture extends Fixture
{
    private $manager;

    public function load(ObjectManager $manager)
    {
        ini_set('memory_limit', '512M');

        $this->manager = $manager;
        $this->mockUsers();
        $this->fakeUsers();
    }

    private function mockUsers()
    {
        // Usuario utilizado para test de login
        $user = new User();
        $user->setAccount(Uuid::uuid4());
        $name = 'Test Login';
        $user->setFirstName($name);
        $user->setLastName('Test Login Lastname');
        $email = "testlogin@qubit.tv";
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setPhone('546666666');
        $user->setRegion('AR');
//        $user->setPassword(UserManager::hashPassword('qubit'));
        $user->setRoles(['USER']);
        $user->setCreated(new \DateTime());
        $user->setUpdated(new \DateTime());

        $profile = new UserProfile();
        $profile->setGender(Constant::GENDER_MALE);
        $profile->setUser($user);
        $profile->setUpdated(new \DateTime());

        $preferences = new UserPreferences();
        $preferences->setUser($user);

        $this->manager->persist($preferences);
        $this->manager->persist($profile);
        $this->manager->persist($user);

        $auth = new Auth();
//        $auth->setUsername($email);
        $auth->setUserId($user->getId());
        $auth->setPassword(UserManager::hashPassword('qubit'));
        $auth->setSalt(null);
        $auth->setCreated(new \DateTime());
        $auth->setUpdated(new \DateTime());
        $this->manager->persist($auth);

        $this->manager->flush();
        $this->manager->clear();

        // Usuario utilizado para test de login V4
        $user = new User();
        $user->setAccount(Uuid::uuid4());
        $name = 'Test Login V4';
        $user->setFirstName($name);
        $user->setLastName('Test Login Lastname V4');
        $email = "testloginv4@qubit.tv";
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setPhone('546666667');
        $user->setRegion('AR');
//        $user->setSalt('Setec Astronomy');
//        $user->setPassword('ca6a9a6b759065c263d957090a0eace7dddb3542');
        $user->setRoles(['USER']);
        $user->setCreated(new \DateTime());
        $user->setUpdated(new \DateTime());

        $profile = new UserProfile();
        $profile->setGender(Constant::GENDER_MALE);
        $profile->setUser($user);
        $profile->setUpdated(new \DateTime());

        $preferences = new UserPreferences();
        $preferences->setUser($user);

        $this->manager->persist($preferences);
        $this->manager->persist($profile);
        $this->manager->persist($user);

        $auth = new Auth();
//        $auth->setUsername($email);
        $auth->setUserId($user->getId());
        $auth->setPassword('ca6a9a6b759065c263d957090a0eace7dddb3542');
        $auth->setSalt('Setec Astronomy');
        $auth->setCreated(new \DateTime());
        $auth->setUpdated(new \DateTime());
        $this->manager->persist($auth);

        $this->manager->flush();
        $this->manager->clear();

        // Usuario utilizado para test de login BO V4
        $user = new User();
        $user->setAccount(Uuid::uuid4());
        $name = 'Test Login BO V4';
        $user->setFirstName($name);
        $user->setLastName('Test Login Lastname BO V4');
        $email = "testloginbov4@qubit.tv";
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setPhone('546666668');
        $user->setRegion('AR');
//        $user->setSalt('Qq6bQ6rGXsDC5JVY5D/BJH3YBD6Wiz8l97Nhedxao.8');
//        $user->setPassword('387c9ce7ee80839c5fbf13865767f737373744b7');
        $user->setRoles(['USER']);
        $user->setCreated(new \DateTime());
        $user->setUpdated(new \DateTime());

        $profile = new UserProfile();
        $profile->setGender(Constant::GENDER_MALE);
        $profile->setUser($user);
        $profile->setUpdated(new \DateTime());

        $this->manager->persist($profile);
        $this->manager->persist($user);

        $auth = new Auth();
//        $auth->setUsername($email);
        $auth->setUserId($user->getId());
        $auth->setPassword('387c9ce7ee80839c5fbf13865767f737373744b7');
        $auth->setSalt('Qq6bQ6rGXsDC5JVY5D/BJH3YBD6Wiz8l97Nhedxao.8');
        $auth->setCreated(new \DateTime());
        $auth->setUpdated(new \DateTime());
        $this->manager->persist($auth);

        $preferences = new UserPreferences();
        $preferences->setUser($user);

        $this->manager->persist($preferences);
        $this->manager->flush();
        $this->manager->clear();
    }

    private function fakeUsers()
    {
        $batchSize = 20;
        // create 20 products! Bam!
        for ($i = 0; $i < 200; $i++) {
            $faker = Factory::create('es_ES');

            $user = new User();
            $user->setAccount(Uuid::uuid4());
            $name = $faker->firstName;
            $user->setFirstName($name);
            $user->setLastName($faker->lastName);
            $email = $i.$faker->email;
            $user->setUsername($email);
            $user->setEmail($email);
            $user->setPhone($faker->phoneNumber);
            $user->setRegion($faker->countryCode);
//            $user->setPassword(UserManager::hashPassword('qubit'));
            $user->setRoles(['USER']);
            $user->setCreated(new \DateTime());
            $user->setUpdated(new \DateTime());


            $profile = new UserProfile();
            $profile->setGender(Constant::GENDER_MALE);
            $profile->setUser($user);
            $profile->setUpdated(new \DateTime());
//            $user->setProfiles([$profile]);
//            dump($faker->firstName);
            $preferences = new UserPreferences();
            $preferences->setUser($user);

            $this->manager->persist($preferences);
            $this->manager->persist($profile);
            $this->manager->persist($user);

            $auth = new Auth();
//            $auth->setUsername($email);
            $auth->setUserId($user->getId());
            $auth->setPassword(UserManager::hashPassword('qubit'));
            $auth->setSalt(null);
            $auth->setCreated(new \DateTime());
            $auth->setUpdated(new \DateTime());
            $this->manager->persist($auth);

            if (($i % $batchSize) === 0) {
                $this->manager->flush();
                $this->manager->clear(); // Detaches all objects from Doctrine!
            }
        }

        $this->manager->flush();
        $this->manager->clear();
    }
}
