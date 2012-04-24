<?php

class Menu_Widget_Menu extends ZendSF_Widget_Abstract
{
    protected $_viewTemplate = 'menu.phtml';

    protected function init()
    {
        $this->_view->addHelperPath(
            realpath(APPLICATION_PATH . '/modules/menu/views/helpers'),
            'Menu_View_Helper'
        );
    }
}

