<?php

class User_Model_DbTable_Row_User extends ZendSF_Model_DbTable_Row_Abstract implements Zend_Acl_Role_Interface
{
    /**
     * @var User_Model_DbTable_Row_Role
     */
    protected $_role;

    public function getFullname()
    {
        return $this->getRow()->title .
            ' ' .
            $this->getRow()->firstname .
            ' ' .
            $this->getRow()->lastname;
    }

    public function getRole()
    {
        if (!$this->_role instanceof User_Model_DbTable_Row_Role) {
            $row = $this->getRow()->findParentRow('User_Model_DbTable_Role', 'role');
            $this->_role = $row->getRow()->role;
        }
        return $this->_role;
    }


    public function getRoleId()
    {
        if (null === $this->getRow()->role) {
            return 'guest';
        }

        return $this->getRow()->role;
    }
}