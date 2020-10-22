<?php

namespace Form\Model;

class Priority {
    
    public $id;
    public $name;

    public function exchangeArray($data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
    }

    public function getArrayCopy() {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

}