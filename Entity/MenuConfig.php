<?php

namespace Meisa\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * MenuConfig
 * @ORM\Table(name = "meisa_menu_Config")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class MenuConfig
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotNull
     * @ORM\Column(name="route_name", type="string", length=255)
     */
    private $routeName;

    /**
     * @var string
     * @Assert\NotNull
     * @ORM\Column(name="display_name", type="string", length=255)
     */
    private $displayName;

    /**
     * @var string
     * @Assert\NotNull
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="MenuConfigParameter", mappedBy="menuConfig", cascade={ "all"}, orphanRemoval=true)
     */
    protected $parameters;

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * @param string $routeName
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

/////////////////////////////////////////////////////////
    public function __toString()
    {
        if ($this->displayName) return $this->displayName;
        return '';
    }

/////////////////////////////////////////////////////////
    /**
     * Add page_image
     *
     * @param Menu $menuItem
     * @return Menu
     */
    public function addParameters($parameter)
    {
        $this->parameters[] = $parameter;
        return $this;
    }

    /**
     * Remove Menu
     *
     * @param Menu $menu
     */
    public function removeParameters($parameter)
    {
        $this->parameters->removeElement($parameter);
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $menuItems
     */
    public function setParameters($parameters)
    {
        $this->parameters = new ArrayCollection();
        foreach ($parameters as $parameter) {
            $parameter->setMenuConfig($this);
            $this->addParameters($parameter);
        }
    }
}
