<?php

class User_Form_Base extends ZendSF_Form_Abstract
{
    public function init()
    {
        // add path to custom validators.
        $this->addElementPrefixPath(
            'User_Validate',
            APPLICATION_PATH . '/modules/user/models/validate/',
            'validate'
        );

        $this->addElement('select', 'title', array(
            'required'      => true,
            'label'         => 'Title',
            'multiOptions'  => array(
                'Mrs'   => 'Mrs',
                'Ms'    => 'Ms',
                'Miss'  => 'Miss',
                'Mr'    => 'Mr'
            )
        ));

        $this->addElement('text', 'firstname', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                'Alpha',
                array('StringLength', true, array(3, 128))
            ),
            'required'      => true,
            'label'         => 'First Name'
        ));

        $this->addElement('text', 'lastname', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                'Alpha',
                array('StringLength', true, array(3, 128))
            ),
            'required'      => true,
            'label'         => 'Last Name'
        ));

        $this->addElement('text', 'email', array(
            'filters'       => array('StringTrim', 'StringToLower'),
            'validators'    => array(
                array('StringLength', true, array(3, 128)),
                array('EmailAddress', true, array(
                    'mx'    => true
                )),
                array('UniqueEmail', false, array(
                    new User_Model_User()
                ))
            ),
            'required'      => true,
            'label'         => 'Email'
        ));

        $this->addElement('password', 'passwd', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringTrim', true, array(6, 128))
            ),
            'required'      => true,
            'label'         => 'Password'
        ));

        $this->addElement('password', 'passwdVerify', array(
            'filters'       => array('StringTrim'),
            'validators'    => array('PasswordVerification'),
            'required'      => true,
            'label'         => 'Confirm Password'
        ));

        $this->addElement('submit', 'submit', array(
            'required'      => false,
            'ignore'        => true,
            'decorators'    => array('ViewHelper', array(
                'HtmlTag', array(
                    'tag'   => 'dd',
                    'id'    => 'form-submit'
                )
            ))
        ));

        $this->addElement('hidden', 'userId', array(
            'filters'       => array('StringTrim'),
            'required'      => true,
            'decorators'    => array('ViewHelper', array(
                'HtmlTag', array(
                    'tag'   => 'dd',
                    'class' => 'noDisplay'
                )
            ))
        ));
    }
}