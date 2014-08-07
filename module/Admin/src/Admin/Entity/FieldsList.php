<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FieldsList
 *
 * @ORM\Table(name="fields_list", indexes={@ORM\Index(name="entity_id", columns={"entity_id"})})
 * @ORM\Entity
 */
class FieldsList extends AbstractEntity
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
     * @var boolean
     *
     * @ORM\Column(name="position", type="boolean", nullable=true)
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=100, nullable=true)
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="entity_name", type="string", length=100, nullable=true)
     */
    private $entityName;

    /**
     * @var \Admin\Entity\Entity
     *
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Entity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entity_id", referencedColumnName="id")
     * })
     */
    private $entity;



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
     * @return FieldsList
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
     * Set position
     *
     * @param boolean $position
     * @return FieldsList
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return boolean 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return FieldsList
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return FieldsList
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set entityName
     *
     * @param string $entityName
     * @return FieldsList
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }

    /**
     * Get entityName
     *
     * @return string 
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * Set entity
     *
     * @param \Admin\Entity\Entity $entity
     * @return FieldsList
     */
    public function setEntity(\Admin\Entity\Entity $entity = null)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return \Admin\Entity\Entity 
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
