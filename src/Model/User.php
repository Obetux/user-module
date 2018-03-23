<?php
/**
 * Created by PhpStorm.
 * User: Claudio
 * Date: 28/10/2017
 * Time: 16:46
 */

namespace App\Model;

use Qubit\Bundle\UtilsBundle\Context\Context;
use App\Entity\User as UserEntity;
use App\Entity\UserProfile as UserProfileEntity;
use App\Entity\UserPreferences as UserPreferencesEntity;
use App\Service\UserService;
use Doctrine\Common\Collections\ArrayCollection;
use Firebase\JWT\JWT;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @SWG\Definition(
 *     type="object",
 *     definition="User"
 * )
 * @Serializer\ExclusionPolicy("all")
 */
class User implements \JsonSerializable
{
    const USER_KEY = 'jjskkduwhspfwk';

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(description = "User id",
     *     example=0f777d1d-5dd6-4812-8e1f-e3e6d1ee0f55, default = null)
     */
    private $id;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(description = "Account",
     *     example=02eb3247-42d6-4be2-bbf3-d2ffd19461eb, default = null)
     */
    private $account;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="username",
     *   type="string",
     *   example="PatoDonald",
     *   description="username"
     * )
     */
    private $username;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="firstName",
     *   type="string",
     *   example="Pato",
     *   description="First Name"
     * )
     */
    private $firstName;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="lastName",
     *   type="string",
     *   example="Donald",
     *   description="Last Name"
     * )
     */
    private $lastName;
    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="email",
     *   type="string",
     *   example="pato.donald@warner.com",
     *   description="User Email"
     * )
     */
    private $email;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="phone",
     *   type="string",
     *   example="5555555",
     *   description="User Phone"
     * )
     */
    private $phone;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="region",
     *   type="string",
     *   example="AR",
     *   description="User Region"
     * )
     */
    private $region;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="lastLogin",
     *   type="string",
     *   example="2017-08-31 15:40:35",
     *   description="User Last Login"
     * )
     */
    private $lastLogin;
    private $profiles;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="hash",
     *   type="string",
     *   example="asdasdfffg.weasdasd.ttrasd",
     *   description="User Autologin Hash"
     * )
     */
    private $hash;

    /**
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="requireNewPassword",
     *   type="boolean",
     *   example=false,
     *   description="Indicate if the user need a new password"
     * )
     */
    private $requireNewPassword;

    /**
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="validatedUser",
     *   type="boolean",
     *   example=true,
     *   description="Indicate if the user has be validated"
     * )
     */
    private $validatedUser;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="gender",
     *   type="string",
     *   example="M",
     *   description="User Gender"
     * )
     */
    private $gender;

    /**
     * @Serializer\Type("string")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="avatar",
     *   type="string",
     *   example="//st.qubit.tv/assets/public/qubit/qubit-ar/avatar/04.svg",
     *   description="User Avatar URL"
     * )
     */
    private $avatar;

    /**
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="emailNotification",
     *   type="boolean",
     *   example=true,
     *   description="Email Notification"
     * )
     */
    private $emailNotification;

    /**
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="inAppNotification",
     *   type="boolean",
     *   example=true,
     *   description="in App Notification"
     * )
     */
    private $inAppNotification;

    /**
     * @Serializer\Type("boolean")
     * @Serializer\Expose
     *
     * @SWG\Property(
     *   property="pushNotification",
     *   type="boolean",
     *   example=true,
     *   description="push Notification"
     * )
     */
    private $pushNotification;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->profiles = new ArrayCollection();
    }

    public function hydrate(UserEntity $user)
    {
        $this->id= $user->getId();
        $this->account = $user->getAccount();
        $this->username = $user->getUsername();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->email = $user->getEmail();
        $this->phone = $user->getPhone();
        $this->region = $user->getRegion();

        if ($profile = $user->getProfile()) {
            /** @var  UserProfileEntity $profile */
            $this->gender = $profile->getGender();
            $this->avatar = $profile->getAvatar();
        }

        if ($preferences = $user->getPreferences()) {
            /** @var UserPreferencesEntity $preferences */
            $this->emailNotification = $preferences->isEmailNotification();
            $this->inAppNotification = $preferences->isInAppNotification();
            $this->pushNotification = $preferences->isPushNotification();
        }

        $this->setHash($this->encryptUserHash());
        $this->requireNewPassword = false;
        $this->validatedUser = true;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {

        $user = array(
            'id' => $this->id,
            'account' => $this->account,
            'username' => $this->username,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'region' => $this->region,
            'gender' => $this->gender,
            'avatar' => $this->avatar,
            'emailNotification' => $this->emailNotification,
            'inAppNotification' => $this->inAppNotification,
            'pushNotification' => $this->pushNotification,
            'hash' => $this->encryptUserHash(),
            'requireNewPassword' => $this->requireNewPassword,
            'validatedUser' => $this->validatedUser,
        );

        return $user;
    }


    public function encryptUserHash()
    {
        $iat = time();
        $exp = time() + (31 * 24 * 60 * 60 ); // Adding 1 month;

        $context = Context::getInstance();
        $userHash = [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'deviceId' => $context->getDeviceId(),
        ];
        $token = array(
            "iat" => $iat,
            "exp" => $exp,
            'user' => $userHash,
        );

        return  JWT::encode($token, self::USER_KEY);
    }

    public static function decryptUserHash($hash)
    {
        try {
            $decoded = JWT::decode($hash, self::USER_KEY, array('HS256'));
            return property_exists($decoded, 'user') ? $decoded->user : null ;
        } catch (\Exception $exception) {
            return null;
        }
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     * @return User
     */
    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getfirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return User
     */
    public function setfirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getlastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return User
     */
    public function setlastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param mixed $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * @param ArrayCollection $profiles
     * @return User
     */
    public function setProfiles($profiles)
    {
        $this->profiles = $profiles;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     *
     * @return User
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     *
     * @return User
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequireNewPassword()
    {
        return $this->requireNewPassword;
    }

    /**
     * @param mixed $requireNewPassword
     * @return User
     */
    public function setRequireNewPassword($requireNewPassword)
    {
        $this->requireNewPassword = $requireNewPassword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidatedUser()
    {
        return $this->validatedUser;
    }

    /**
     * @param mixed $validatedUser
     * @return User
     */
    public function setValidatedUser($validatedUser)
    {
        $this->validatedUser = $validatedUser;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailNotification()
    {
        return $this->emailNotification;
    }

    /**
     * @param mixed $emailNotification
     * @return User
     */
    public function setEmailNotification($emailNotification)
    {
        $this->emailNotification = $emailNotification;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInAppNotification()
    {
        return $this->inAppNotification;
    }

    /**
     * @param mixed $inAppNotification
     * @return User
     */
    public function setInAppNotification($inAppNotification)
    {
        $this->inAppNotification = $inAppNotification;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPushNotification()
    {
        return $this->pushNotification;
    }

    /**
     * @param mixed $pushNotification
     * @return User
     */
    public function setPushNotification($pushNotification)
    {
        $this->pushNotification = $pushNotification;
        return $this;
    }





}