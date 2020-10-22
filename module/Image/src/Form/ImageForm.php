<?php
namespace Image\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;

class ImageForm extends Form {

    public function __construct($name=null) 
    {
        parent::__construct('image');
        $this->setAttribute('method', 'POST');
        
        $this->add([
            'name' => 'id',
            'type' => 'hidden'
        ]);
        
        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Name'
            ]
        ]);
        
        $file = new Element\File('image');
        $file->setLabel('Single file input');
        $this->add($file);
        
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Save',
                'id' => 'buttonSave'
            ]
        ]);


    }
}