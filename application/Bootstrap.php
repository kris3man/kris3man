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

    private $_errorType = array (
		E_ERROR				=> 'ERROR',
		E_WARNING			=> 'WARNING',
		E_PARSE				=> 'PARSING ERROR',
		E_NOTICE			=> 'NOTICE',
		E_CORE_ERROR		=> 'CORE ERROR',
		E_CORE_WARNING		=> 'CORE WARNING',
		E_COMPILE_ERROR		=> 'COMPILE ERROR',
		E_COMPILE_WARNING	=> 'COMPILE WARNING',
		E_USER_ERROR		=> 'USER ERROR',
		E_USER_WARNING		=> 'USER WARNING',
		E_USER_NOTICE		=> 'USER NOTICE',
		E_STRICT			=> 'STRICT NOTICE',
		E_RECOVERABLE_ERROR	=> 'RECOVERABLE ERROR',
		E_DEPRECATED		=> 'DEPRECATED',
		E_USER_DEPRECATED	=> 'USER DEPRECATED'
	);

    private $_errorHandlerMap = array (
		E_NOTICE            => Zend_Log::NOTICE,
        E_USER_NOTICE       => Zend_Log::NOTICE,
        E_WARNING           => Zend_Log::WARN,
        E_CORE_WARNING      => Zend_Log::WARN,
        E_USER_WARNING      => Zend_Log::WARN,
        E_ERROR             => Zend_Log::ERR,
        E_USER_ERROR        => Zend_Log::ERR,
        E_CORE_ERROR        => Zend_Log::ERR,
        E_RECOVERABLE_ERROR => Zend_Log::ERR,
        E_STRICT            => Zend_Log::DEBUG,
        E_DEPRECATED        => Zend_Log::DEBUG,
        E_USER_DEPRECATED   => Zend_Log::DEBUG
	);

    /**
     * EOL character
     */
    const EOL = "\n";

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
        $this->bootstrap('frontController');
        $errorHandler = set_error_handler(array($this,'errorHandler'));
        $logger = new Zend_Log();
        $dbLog = new Zend_Log();

        if ('production' == $this->getEnvironment()) {
            $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH.'/../data/logs/app.log');
            $dbWriter = new Zend_Log_Writer_Stream(APPLICATION_PATH.'/../data/logs/app-db.log');
            $filter = new Zend_Log_Filter_Priority(Zend_Log::INFO);
            $logger->addFilter($filter);
            //$dbLog->addFilter($filter);
        } else {
            $writer = new Zend_Log_Writer_Firebug();
            $dbWriter = new Zend_Log_Writer_Firebug();
            $writer->setPriorityStyle(8, 'TABLE');
            $logger->addPriority('TABLE', 8);
        }

        $logger->addWriter($writer);
        $dbLog->addWriter($dbWriter);

        $this->_logger = $logger;
        Zend_Registry::set('log', $logger);
        Zend_Registry::set('dblog', $dbLog);
    }

    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        if ('production' == $this->getEnvironment()) {
            if (isset($this->_errorHandlerMap[$errno])) {
                $priority = $this->_errorHandlerMap[$errno];
            } else {
                $priority = Zend_Log::INFO;
            }
            $errorMessage = self::EOL . 'Error ' . $this->_errorType[$errno] . self::EOL;
            $errorMessage .= 'ERROR NO : ' . $errno . self::EOL;
            $errorMessage .= 'TEXT : ' . $errstr . self::EOL;
            $errorMessage .= 'LOCATION : ' . $errfile . ' ' . $errline . self::EOL;
            $errorMessage .= 'DATE : ' . date('F j, Y, g:i a') . self::EOL;
            $errorMessage .= '------------------------------------' . self::EOL;
            $this->_logger->log($errorMessage, $priority);
        } else {
            $errorMessage = array('Error : ' . $this->_errorType[$errno], array(
                array('', ''),
                array('Error No', $errno),
                array('Message', $errstr),
                array('File Name', $errfile),
                array('Line No', $errline),
                //array('Context', $errcontext)
            ));

            $this->_logger->table($errorMessage);
        }

        return true;
    }

    /**
     * Sets the Database profiler for the application.
     */
    protected function _initDbProfiler()
    {
        if ('production' !== $this->getEnvironment()) {
            $this->bootstrap('db');
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $profiler->setEnabled(true);
            $this->getPluginResource('db')
                 ->getDbAdapter()
                 ->setProfiler($profiler);
        }
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
        //Zend_Controller_Action_HelperBroker::addHelper(new ZendSF_Controller_Helper_MobileContext());
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
     *Set up locale
     */
    protected function _initLocale()
    {
        $locale = new Zend_Locale('en_GB');
        Zend_Registry::set('Zend_Locale', $locale);
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
}

