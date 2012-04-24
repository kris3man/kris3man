<?php

class User_Form_Register extends User_Form_Base
{
    public function init()
    {
        // make sure parent is called.
        parent::init();
        
        // specialize this form.
        $this->removeElement('userId');
        $this->getElement('submit')->setLabel('Register');
    }
}