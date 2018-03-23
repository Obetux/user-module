<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthRepository")
 * @ORM\Table(
 *     indexes={
 *          @ORM\Index(name="user", columns={"user_id"})
 *     })
 */
class Auth
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    protected $userId;

    /**
     * @var string $salt
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $salt;

    /**
     * @var string $password
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected $password;

    /**
     * @var string $lastLogin
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var string $confirmationToken
     *
     * @ORM\Column(type="string", length=180, nullable=true, unique=true)
     */
    protected $confirmationToken;

    /**
     * @var string $passwordRequestedAt
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $passwordRequestedAt;

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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return Auth
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     *
     * @return Auth
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     *
     * @return Auth
     */
    public function setSalt(?string $salt): Auth
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return Auth
     */
    public function setPassword(string $password): Auth
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime $lastLogin
     *
     * @return Auth
     */
    public function setLastLogin(\DateTime $lastLogin): Auth
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmationToken(): string
    {
        return $this->confirmationToken;
    }

    /**
     * @param string $confirmationToken
     *
     * @return Auth
     */
    public function setConfirmationToken(string $confirmationToken): Auth
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordRequestedAt(): string
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param string $passwordRequestedAt
     *
     * @return Auth
     */
    public function setPasswordRequestedAt(string $passwordRequestedAt): Auth
    {
        $this->passwordRequestedAt = $passwordRequestedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     *
     * @return Auth
     */
    public function setCreated(\DateTime $created): Auth
    {
        $this->created = $created;

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
     *
     * @return Auth
     */
    public function setUpdated(\DateTime $updated): Auth
    {
        $this->updated = $updated;

        return $this;
    }



}
