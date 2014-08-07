<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity
 *
 * @ORM\Table(name="entity")
 * @ORM\Entity
 */
class Entity extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="table", type="string", length=100, nullable=true)
     */
    private $table;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="plural_name", type="string", length=255, nullable=true)
     */
    private $pluralName;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Entity
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set table
     *
     * @param string $table
     * @return Entity
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get table
     *
     * @return string 
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Entity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set pluralName
     *
     * @param string $pluralName
     * @return Entity
     */
    public function setPluralName($pluralName)
    {
        $this->pluralName = $pluralName;

        return $this;
    }

    /**
     * Get pluralName
     *
     * @return string 
     */
    public function getPluralName()
    {
        return $this->pluralName;
    }
}
