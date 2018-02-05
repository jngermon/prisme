<?php

namespace AppBundle\Doctrine\Common;

use Doctrine\Common\EventArgs;

class EntityLoadEventArgs extends EventArgs
{
    protected $classname;

    protected $id;

    protected $entity;

    public function __construct($classname, $id)
    {
        $this->classname = $classname;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getClassname()
    {
        return $this->classname;
    }

    /**
     * @param string $classname
     */
    public function setClassname($classname)
    {
        $this->classname = $classname;

        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param entity $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }
}
