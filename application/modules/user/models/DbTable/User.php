<?php

class User_Model_DbTable_User extends ZendSF_Model_DbTable_Abstract
{
    protected $_name = 'user';
    protected $_primary = 'userId';
    protected $_rowClass = 'User_Model_DbTable_Row_User';

    protected $_referenceMap = array(
        'role' => array(
            'columns'       => 'roleId',
            'refTableClass' => 'User_Model_DbTable_Role',
            'refColumns'    => 'roleId'
        )
    );

    public function getUserById($id)
    {
        return $this->find($id)->current();
    }

    public function getUserByEmail($email, $ignoreUser=null)
    {
        $select = $this->select();
        $select->where('email = ?', $email);

        if (null !== $ignoreUser) {
            $select->where('email != ?', $ignoreUser->email);
        }

        return $this->fetchRow($select);
    }

    public function getUserByUsername($username, $ignoreUser=null)
    {
        $select = $this->select();
        $select->where('username = ?', $username);

        if (null !== $ignoreUser) {
            $select->where('username != ?', $ignoreUser->email);
        }

        return $this->fetchRow($select);
    }

    public function getUsers($paged=null, $order=null)
    {
        if (true === is_array($order)) {
            $select->order($order);
        }

        if (null !== $paged) {
            $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);

            $count = clone $select;
            $count->reset(Zend_Db_Select::COLUMNS);
            $count->reset(Zend_Db_Select::FROM);
            $count->from(
                'user',
                new Zend_Db_Expr('COUNT(*) AS `zend_paginator_row_count`')
            );
            $adapter->setRowCount($count);

            $itemsPerPage = Zend_Registry::get('config')
                ->paginate
                ->user
                ->users;

            $paginator = new Zend_Paginator($adapter);
            $paginator->setItemCountPerPage($itemsPerPage)
                ->setCurrentPageNumber((int) $paged);
            return $paginator;
        }

        return $this->fetchAll($select);
    }
}