<?php

class User_Validate_UniqueEmail extends Zend_Validate_Abstract
{
    const EMAIL_EXISTS = 'emailExists';

    protected $_messageTemplate = array(
        self::EMAIL_EXISTS => 'Email "%value%" already exists in our system'
    );

    public function __construct(User_Model_User $model)
    {
        $this->_model = $model;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);

        $currentUser = isset($context['userId']) ?
            $this->_model->getUserById($comtext['userId']) : null;
        $user = $this->_model->getUserByEmail($value, $currentUser);

        if (null === $user) {
            return true;
        }

        $this->_error(self::EMAIL_EXISTS);
        return false;
    }
}