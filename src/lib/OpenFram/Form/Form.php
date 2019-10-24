<?php


namespace OpenFram\Form;

use OpenFram\Entity;

class Form
{

    protected $entity;
    public $fields = [];

    /**
     * Form constructor.
     * @param $entity
     */
    public function __construct(Entity $entity)
    {
        $this->setEntity($entity);
    }

    public function add(Field $field)
    {
        $attr = 'get'.ucfirst($field->getAttributes()['name']);

        $field->setValue($this->entity->$attr());

        $this->fields[] = $field;
        return $this;
    }

    public function createView()
    {
        $view = '';
        foreach ($this->fields as $field) {
            $view .= $field->buildWidget();
        }

        return $view;
    }

    public function isValid()
    {
        $valid = true;
        foreach ($this->fields as $field) {
            if (!$field->isValid()) {
                $valid = false;
            }
        }
        return $valid;
    }



    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     */
    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;
    }
}
