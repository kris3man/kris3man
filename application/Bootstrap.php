<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * @var Zend_Log
     */
    protected $_logger;

    /**
     * @var Zend_Application_Module_Autoloader
     */
    protected $_resourceLoader;

    /**
     * @var Zend_View
     */
    protected $_view;

    /**
     * @var Zend_Controller_Front
     */
    public $frontController;

    /**
     * Adds a cache to production environment for plugin loader.
     */
    protected function _initPluginLoaderCache()
    {
        if ('production' == $this->getEnvironment()) {
            $classFileIncCache =
                APPLICATION_PATH .
                '/../data/cache/pluginLoaderCache.php';

            if (file_exists($classFileIncCache)) {
                include_once $classFileIncCache;
            }

            Zend_Loader_PluginLoader::setIncludeFileCache(
                $classFileIncCache
            );
        }
    }

    /**
     * Sets the logging for the application.
     */
    protected function _initLogging()
    {
        $log = new ZendSF_Log($this, true);
        $this->_logger = Zend_Registry::get('log');
        return $log;
    }

    /**
     * Caches database table information.
     */
    protected function _initDbCaches()
    {
        if ('production' == $this->getEnvironment()) {

            $cache = Zend_Cache::factory(
                'Core',
                'File',
                array(
                    // set cache life time for 30 days
                    'lifetime'                => 60*60*24*30,
                    'automatic_serialization' => true
                ),
                array(
                    'cache_dir' => APPLICATION_PATH . '/../data/cache'
                )
            );
            Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
        }
    }

    /**
     * Add Global Action Helpers
     */
    protected function _initActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addHelper(new ZendSF_Controller_Helper_Acl());
        Zend_Controller_Action_HelperBroker::addHelper(new ZendSF_Controller_Helper_Service());
        //Zend_Controller_Action_HelperBroker::addHelper(new ZendSF_Controller_Helper_SSL());
        Zend_Controller_Action_HelperBroker::addHelper(new ZendSF_Controller_Helper_MobileContext());
    }

    /**
     * Sets up the helper paths for the application.
     */
    protected function _initGlobalViewHelperPath()
    {
        $this->bootstrap('view');

        $this->_view = $this->getResource('view');

        $this->_view->addHelperPath(
                APPLICATION_PATH . '/../library/ZendSF/View/Helper',
                'ZendSF_View_Helper'
        );
    }

    /**
     * Set up global configuration
     */
    protected function _initConfig()
    {
        $options = new Zend_Config_Ini(APPLICATION_PATH . '/configs/options.ini');
        Zend_Registry::set('config', $options);

        $this->_view->headTitle($options->site->title)
                ->setSeparator(' - ');
    }

    /**
     *Set up locale
     */
    protected function _initLocale()
    {
        $locale = new Zend_Locale('en_GB');
        Zend_Registry::set('Zend_Locale', $locale);
    }

    protected function _initViewSettings()
    {
        Zend_Dojo::enableView($this->_view);
        Zend_Dojo_View_Helper_Dojo::setUseDeclarative();

        // configure Dojo view helper, disable
        $this->_view->dojo()
            ->enable()
            ->addStyleSheetModule('dijit.themes.claro')
            ->setDjConfigOption('parseOnLoad', true)
            ->setCdnBase(Zend_Dojo::CDN_BASE_GOOGLE)
            ->setCdnVersion('1.7.2')
            ->setCdnDojoPath('/dojo/dojo.js')
            //->setCdnDojoPath(Zend_Dojo::CDN_DOJO_PATH_GOOGLE)
            ->addStyleSheetModule('dijit.themes.claro')
            ->useCdn()
            ;
    }
}

