<?php

class User_Form_edit extends User_Form_Register
{
    public function init()
    {
        // make sure parent is called.
        parent::init();

        // specialize this form.
        $this->getElement('passwd')->setRequired(false);
        $this->getElement('passwdVerify')->setRequired(false);
        $this->getElement('submit')->setLabel('Save User');
    }
}