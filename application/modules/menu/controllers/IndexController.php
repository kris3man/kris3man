<?php

class Menu_IndexController extends Zend_Controller_Action
{

    /**
     * @var Shop_Model_Catalog
     */
    protected $_model;

    public function init()
    {

    }

    public function indexAction()
    {}

    public function sitemapAction()
    {
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $menu = new Zend_Config_Xml(
            APPLICATION_PATH . '/configs/menus/mainMenu.xml', 'nav'
        );

        $container = new Zend_Navigation($menu);

        $sitemap = $this->view->navigation($container)
            ->sitemap()
            ->setUseXmlDeclaration(true)
            ->setFormatOutput(true)
            ->render();

        $this->getResponse()
            ->setHeader('Content-Type', 'application/xml')
            ->setBody($sitemap);
    }
}

