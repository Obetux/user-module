<?php
/**
 * Created by PhpStorm.
 * User: Claudio
 * Date: 16/10/2017
 * Time: 20:33
 */

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="profile_image")
 * @ORM\Entity()
 */
class ProfileImage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(type="string",  length=30, nullable=false)
     */
    protected $name;

    /**
     * @var string $pathSVG
     *
     * @ORM\Column(type="string",  length=180, nullable=true)
     */
    protected $pathSVG;

    /**
     * @var string $pathSmall
     *
     * @ORM\Column(type="string",  length=180, nullable=true)
     */
    protected $pathSmall;

    /**
     * @var string $pathMedium
     *
     * @ORM\Column(type="string",  length=180, nullable=true)
     */
    protected $pathMedium;

    /**
     * @var string $pathLarge
     *
     * @ORM\Column(type="string",  length=180, nullable=true)
     */
    protected $pathLarge;

    /**
     * @var string $selectable
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $selectable;

    /**
     * @var string $enabled
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $enabled;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ProfileImage
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ProfileImage
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathSVG()
    {
        return $this->pathSVG;
    }

    /**
     * @param string $pathSVG
     * @return ProfileImage
     */
    public function setPathSVG($pathSVG)
    {
        $this->pathSVG = $pathSVG;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathSmall()
    {
        return $this->pathSmall;
    }

    /**
     * @param string $pathSmall
     * @return ProfileImage
     */
    public function setPathSmall($pathSmall)
    {
        $this->pathSmall = $pathSmall;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathMedium()
    {
        return $this->pathMedium;
    }

    /**
     * @param string $pathMedium
     * @return ProfileImage
     */
    public function setPathMedium($pathMedium)
    {
        $this->pathMedium = $pathMedium;
        return $this;
    }

    /**
     * @return string
     */
    public function getPathLarge()
    {
        return $this->pathLarge;
    }

    /**
     * @param string $pathLarge
     * @return ProfileImage
     */
    public function setPathLarge($pathLarge)
    {
        $this->pathLarge = $pathLarge;
        return $this;
    }

    /**
     * @return string
     */
    public function getSelectable()
    {
        return $this->selectable;
    }

    /**
     * @param string $selectable
     * @return ProfileImage
     */
    public function setSelectable($selectable)
    {
        $this->selectable = $selectable;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param string $enabled
     * @return ProfileImage
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }



}