<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User
{
    const ROLE_DEFAULT = 'ROLE_USER';

    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $id;

    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @ORM\Column(type="uuid", unique=true)
     */
    protected $account;

    /**
     * @var string $username
     *
     * @ORM\Column(type="string", length=100, nullable=false, unique=true)
     */
    protected $username;

    /**
     * @var string $firstName
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $firstName;

    /**
     * @var string $lastName
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    protected $lastName;

    /**
     * @var string $email
     *
     * @ORM\Column(type="string", length=180, nullable=true, unique=true)
     */
    protected $email;

    /**
     * @var string $phone
     *
     * @ORM\Column(type="string", length=15, nullable=true, unique=true)
     */
    protected $phone;

     /**
     * @var string $region
     *
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    protected $region;

     /**
     * @var string $roles
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $roles;

    /**
     * @var \DateTime $created
     *
     * Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime $updated
     *
     * Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * One User has One Profile.
     * @ORM\OneToOne(targetEntity="UserProfile", mappedBy="user", fetch="EAGER")
     */
    protected $profile;

    /**
     * One User has One User Preferences.
     * @ORM\OneToOne(targetEntity="UserPreferences", mappedBy="user", fetch="EAGER")
     */
    protected $preferences;


    public function __construct()
    {

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
     * @return \Ramsey\Uuid\Uuid
     */
    public function getAccount(): \Ramsey\Uuid\Uuid
    {
        return $this->account;
    }

    /**
     * @param \Ramsey\Uuid\Uuid $account
     * @return User
     */
    public function setAccount(\Ramsey\Uuid\Uuid $account): User
    {
        $this->account = $account;
        return $this;
    }


    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }


    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = $this->roles;

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     * @return User
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
        return $this;
    }



    /**
     *
     */
    public function __toString()
    {
        return (string)  $this->getUsername();
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return User
     */
    public function setPhone(string $phone): User
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     *
     * @return User
     */
    public function setRegion(string $region): User
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreferences()
    {
        return $this->preferences;
    }

    /**
     * @param mixed $preferences
     * @return User
     */
    public function setPreferences($preferences)
    {
        $this->preferences = $preferences;
        return $this;
    }




}
