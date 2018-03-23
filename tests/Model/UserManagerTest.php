<?php
/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 06/12/2017
 * Time: 14:36
 */

namespace App\Tests\Model;

use App\Model\UserManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserManagerTest extends TestCase
{
    public function testHashPassword()
    {
        $plainPassword = 'password';
        $hashedPassword = UserManager::hashPassword($plainPassword);

        $this->assertEquals(true, is_string($hashedPassword));
    }

    public function testHashPasswordFail()
    {
        $plainPassword = '';
        $hashedPassword = UserManager::hashPassword($plainPassword);
        $this->assertEquals(null, is_string($hashedPassword));

        $plainPassword = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

        $exep = null;
        try {
            $hashedPassword = UserManager::hashPassword($plainPassword);
        } catch (\Exception $exep) {
        }
        $this->assertInstanceOf(BadCredentialsException::class, $exep);
    }

    public function testIsPasswordValid()
    {
        $plainPassword = 'password';
        $hashedPassword = UserManager::hashPassword($plainPassword);

        $this->assertEquals(true, UserManager::isPasswordValid($hashedPassword, $plainPassword, null));
        // Prueba la version vieja de validar passwords en V4. No poseo forma de generar la password
        $this->assertEquals(
            true,
            UserManager::isPasswordValid('ca6a9a6b759065c263d957090a0eace7dddb3542', 'megadeth', 'Setec Astronomy')
        );

        // Prueba la version vieja de validar passwords en V4. No poseo forma de generar la password
        $this->assertEquals(
            true,
            UserManager::isPasswordValid(
                '387c9ce7ee80839c5fbf13865767f737373744b7',
                'hangar18',
                'Qq6bQ6rGXsDC5JVY5D/BJH3YBD6Wiz8l97Nhedxao.8'
            )
        );
    }

}
