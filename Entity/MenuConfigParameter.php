<?php

namespace Meisa\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Parameter
 * @ORM\Table(name = "meisa_menu_config_parameter")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class MenuConfigParameter
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
     * @ORM\Column(name="parameter_key", type="string", length=255)
     */
    private $parameterKey;

    /**
     * @var string
     * @ORM\Column(name="key_getter_function", type="string", length=255,nullable=true)
     */
    private $keyGetterFunction;

    /**
     * @var string
     * @ORM\Column(name="link_display_name", type="string", length=255,nullable=true)
     */
    private $linkDisplayName;


    /**
     * @var string
     * @ORM\Column(name="entity_class_name", type="string", length=255,nullable=true)
     */
    private $entityClassName;


    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="MenuConfig" , cascade={"all"},inversedBy="parameters" )
     * @ORM\JoinColumn(name="menu_config_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $menuConfig;

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
    public function getParameterKey()
    {
        return $this->parameterKey;
    }

    /**
     * @param string $parameterKey
     */
    public function setParameterKey($parameterKey)
    {
        $this->parameterKey = $parameterKey;
    }


    /**
     * @return string
     */
    public function getKeyGetterFunction()
    {
        return $this->keyGetterFunction;
    }

    /**
     * @param string $keyGetterFunction
     */
    public function setKeyGetterFunction($keyGetterFunction)
    {
        $this->keyGetterFunction = $keyGetterFunction;
    }

    /**
     * @return string
     */
    public function getMenuConfig()
    {
        return $this->menuConfig;
    }

    /**
     * @param string $menuConfig
     */
    public function setMenuConfig($menuConfig)
    {
        $this->menuConfig = $menuConfig;
    }

    /**
     * @return string
     */
    public function getEntityClassName()
    {
        return $this->entityClassName;
    }

    /**
     * @param string $entityClassName
     */
    public function setEntityClassName($entityClassName)
    {
        $this->entityClassName = $entityClassName;
    }

    /**
     * @return string
     */
    public function getLinkDisplayName()
    {
        return $this->linkDisplayName;
    }

    /**
     * @param string $LinkDisplayName
     */
    public function setLinkDisplayName($linkDisplayName)
    {
        $this->linkDisplayName = $linkDisplayName;
    }
    function __toString()
    {
        if ($this->getParameterKey()) return $this->getParameterKey();
        return '';
    }

}
