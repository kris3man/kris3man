<?php

class User_Model_User extends ZendSF_Model_Acl_Abstract
{
    public function getUserById($id)
    {
        $id = (int) $id;
        return $this->getDbTable('User')->getUserById($id);
    }
    
    public function getUserByEmail($email, $ignoreUser=null)
    {
        return $this->getDbTable('User')
            ->getUserByEmail($email, $ignoreUser);
    }
    
    public function getUsers($paged=false, $order=null)
    {
        return $this->getDbTable('User')
            ->getUsers($paged, $order);
    }
    
    public function registerUser($post)
    {
        if (!$this->checkAcl('register')) {
            throw new ZendSF_Acl_Exception('Insfficient rights');
        }
        
        $form = $this->getForm('register');
        
        return $this->_save($form, $post, array(
            'role' => 'registered'
        ));
    }
    
    public function saveUser($post)
    {
        if (!$this->checkAcl('saveUser')) {
            throw new ZendSF_Acl_Exception('Insufficient rights');
        }
        
        $form = $this->getForm('edit');
        return $this->_save($form, $post);
    }
    
    protected function _save(Zend_Form $form, array $info, $defaults=array())
    {
        if (!$form->isValid($info)) {
            return false;
        }
        
        // get filtered values.
        $data = $form->getValues();
        
        // password hashing.
        if (array_key_exists('passwd', $data) && '' != $data['passwd']) {
            $data['passwd'] = $this->_createPassword($data['passwd']);
        } else {
            unset ($data['passwd']);
        }
        
        // apply any defaults.
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }
        
        $user = (array_key_exists('userId', $data)) ? 
            $this->getDbTable('User')->getUserById($data['userId']) : null;
        
        return $this->getDbTable('User')
            ->saveRow($data, $user);
    }
    
    private function _createPassword($passwd)
    {
        $auth = Zend_Registry::get('config')
            ->user
            ->auth;
            
        $treatment = $auth->credentialTreatment;
        
        return ZendSF_Utility_Password::$treatment(
            $passwd . $auth->salt
        );
    }
    
    public function setAcl(Zend_Acl $acl)
    {
        parent::setAcl($acl);

        // implement rules here.
        $this->_acl->allow('guest', $this, array('register'))
            ->allow('registered', $this, array('saveUser'))
            ->allow('admin', $this);

        return $this;
    }
}