<?php
/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 05/12/2017
 * Time: 17:21
 */

namespace App\Model;

//use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserManager
{
    const MAX_PASSWORD_LENGTH = 72;
    const BCRYPT_PASSWORD_COST = 4;

    public static function hashPassword(string $plainPassword)
    {

        if (0 === strlen($plainPassword)) {
            return null;
        }

//        $encoder = new BCryptPasswordEncoder(4);
//        $hashedPassword = $encoder->encodePassword($plainPassword, null);
//        return $hashedPassword;
        if (self::isPasswordTooLong($plainPassword)) {
            throw new BadCredentialsException('Invalid password.');
        }

        $options = array('cost' => self::BCRYPT_PASSWORD_COST);

        return password_hash($plainPassword, PASSWORD_BCRYPT, $options);
    }

    /**
     * {@inheritdoc}
     */
    public static function isPasswordValid($encoded, $raw, $salt)
    {
        // Si el salt es null, es un usuario nuevo
        if (is_null($salt)) {
            return !self::isPasswordTooLong($raw) && password_verify($raw, $encoded);
        } else {
            // Checkear la forma de validar las passwords de usuarios legacy
            return self::oldPasswordVerify($encoded, $raw, $salt);
        }
        return false;
    }

    private static function oldPasswordVerify($encoded, $raw, $salt)
    {
//  Mecanismo de encriptacion de password de Plataforma V4/V5
//        $secret = 'Setec Astronomy'
//        $mutated_password = hash_hmac('sha1', $raw, $salt);
//
//  Mecanismo de encriptacion de password de Usuarios de BackOffice V4/V5
//          $password1 = sha1($salt.$raw);

        return self::comparePasswords(hash_hmac('sha1', $raw, $salt), $encoded) ||
            self::comparePasswords(sha1($salt.$raw), $encoded);
    }
    /**
     * Checks if the password is too long.
     *
     * @param string $password The password to check
     *
     * @return bool true if the password is too long, false otherwise
     */
    protected static function isPasswordTooLong($password)
    {
        return strlen($password) > static::MAX_PASSWORD_LENGTH;
    }

    /**
     * Compares two passwords.
     *
     * This method implements a constant-time algorithm to compare passwords to
     * avoid (remote) timing attacks.
     *
     * @param string $password1 The first password
     * @param string $password2 The second password
     *
     * @return bool true if the two passwords are the same, false otherwise
     */
    protected static function comparePasswords($password1, $password2)
    {
        return hash_equals($password1, $password2);
    }
}
