<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserPreferencesRepository")
 * @ORM\Table(name="user_preferences")
 */
class UserPreferences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * One User Preference have One User.
     * @ORM\OneToOne(targetEntity="User", inversedBy="preferences")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var boolean $boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $emailNotification = true;

    /**
     * @var boolean $boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $inAppNotification = true;

    /**
     * @var boolean $boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $pushNotification = true;

    /**
     * @var boolean $boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $allowPurchase = true;

    /**
     * @var string $purchasePin
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $purchasePin = null;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * UserPreferences constructor.
     */
    public function __construct()
    {
        $this->updated = new \DateTime();
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
     * @return UserPreferences
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return UserPreferences
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmailNotification(): bool
    {
        return $this->emailNotification;
    }

    /**
     * @param bool $emailNotification
     * @return UserPreferences
     */
    public function setEmailNotification(bool $emailNotification): UserPreferences
    {
        $this->emailNotification = $emailNotification;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInAppNotification(): bool
    {
        return $this->inAppNotification;
    }

    /**
     * @param bool $inAppNotification
     * @return UserPreferences
     */
    public function setInAppNotification(bool $inAppNotification): UserPreferences
    {
        $this->inAppNotification = $inAppNotification;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPushNotification(): bool
    {
        return $this->pushNotification;
    }

    /**
     * @param bool $pushNotification
     * @return UserPreferences
     */
    public function setPushNotification(bool $pushNotification): UserPreferences
    {
        $this->pushNotification = $pushNotification;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowPurchase(): bool
    {
        return $this->allowPurchase;
    }

    /**
     * @param bool $allowPurchase
     * @return UserPreferences
     */
    public function setAllowPurchase(bool $allowPurchase): UserPreferences
    {
        $this->allowPurchase = $allowPurchase;
        return $this;
    }

    /**
     * @return string
     */
    public function getPurchasePin()
    {
        return $this->purchasePin;
    }

    /**
     * @param string $purchasePin
     * @return UserPreferences
     */
    public function setPurchasePin($purchasePin)
    {
        $this->purchasePin = $purchasePin;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     * @return UserPreferences
     */
    public function setUpdated(\DateTime $updated): UserPreferences
    {
        $this->updated = $updated;
        return $this;
    }


}
