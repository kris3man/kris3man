<?php

class Menu_View_Helper_SiteMenu extends Zend_View_Helper_Navigation_Menu
{
    protected $_acl;

    protected $_identity;

    public function siteMenu($menu=null)
    {
        if (null === $menu || '' === $menu) {
            throw new Zend_View_Exception('You must supply a menu name;');
        }

        $menu = new Zend_Config_Xml(
            APPLICATION_PATH . '/configs/menus/' . $menu . '.xml', 'nav'
        );

        $container = new Zend_Navigation($menu);

        if ($container instanceof Zend_Navigation_Container) {
            $this->setContainer($container);
        }

        $this->setAcl($this->getAcl());
        $this->setRole($this->getRole());

        return $this;
    }

    public function getAcl()
    {
        if (!$this->_acl instanceof ZendSF_Acl_Abstarct) {
            // get the acl model of the current module.
            $module = $this->view->request()->getModuleName();
            $acl = ucfirst($module) . '_Model_Acl_' . ucfirst($module);

            $this->_acl = new $acl();
        }

        return $this->_acl;
    }

    public function getIdentity()
    {
        if (!$this->_identity instanceof Zend_Auth) {
            $this->_identity = Zend_Auth::getInstance();
        }

        return $this->_identity;
    }

    public function getRole()
    {
        if (!$this->_identity instanceof Zend_Auth) {
            $this->getIdentity();
        }

        return ($this->_identity->hasIdentity()) ? $this->_identity->getIdentity()->role : 'guest';
    }
}