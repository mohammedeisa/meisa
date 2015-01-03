<?php

namespace Meisa\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MenuName
 * @ORM\Table(name = "tabssoft_menu")
 * @ORM\Entity(repositoryClass="Meisa\MenuBundle\Entity\MenuNameRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Menu
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     *
     */
    private $links;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", options={"default":1})
     */

    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;


    /**
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="menu", cascade={ "all"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $menuItems;



    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }


    public function __toString()
    {
        if ($this->name) return $this->name;
        return '';
    }


    /**
     * Add page_image
     *
     * @param Menu $menuItem
     * @return Menu
     */
    public function addMenuItems($menuItem)
    {
        $this->menuItems[] = $menuItem;
        return $this;
    }

    /**
     * Remove Menu
     *
     * @param Menu $menu
     */
    public function removeMenuItems($menu)
    {
        $this->menuItems->removeElement($menu);
    }

    /**
     * @return mixed
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     * @param mixed $menuItems
     */
    public function setMenuItems($menuItems)
    {
        $this->menuItems = new ArrayCollection();
        foreach ($menuItems as $menuItem) {
            $menuItem->setMenu($this);
            $this->addMenuItems($menuItem);
        }
    }

    /**
     * @ORM\PostPersist
     * @ORM\PrePersist
     * @ORM\PostUpdate
     */
    public function prePersist()
    {
        $this->slug = str_replace(" ", "_", strtolower($this->name)) . '_' . $this->id;
    }

}
