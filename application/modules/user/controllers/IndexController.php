<?php

class User_IndexController extends Zend_Controller_Action
{
    protected $_model;
    protected $_authService;

    public function init()
    {
        // get the default model.
        $this->_model = new User_Model_User();
        $this->_authService = new ZendSF_Service_Authentication();

        // add forms.
        $this->view->registerForm = $this->getRegistrationForm();
        $this->view->loginForm = $this->getLoginForm();
        $this->view->userForm = $this->getUserForm();
    }

    public function indexAction()
    {
        if (!$this->_helper->acl('User')) {
            return $this->_forward('login');
        }

        $userId = $this->_authService->getIdentity()->userId;
        $this->view->user = $this->_model->getUserById($userId);
        $this->view->userForm = $this->getUserForm()->populate(
            $this->view->user->toArray()
        );
    }

    public function loginAction()
    {}

    public function authenticateAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('login');
        }

        // validate.
        $form = $this->_forms['login'];

        if (!$form->isValid($request->getPost())) {
            return $this->render('login');
        }

        if (false === $this->_authService->authenticate($form->getValues())) {
            $form->setDescription('Login failed, please try again.');
            return $this->render('login');
        }

        return $this->_helper->redirector('index');
    }

    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_helper->redirector('index');
    }

    public function saveAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('index');
        }

        if (false === $this->_model->saveUser($request->getPost())) {
            return $this->render('index');
        }

        return $this->_helper->redirector('index');
    }

    public function registerAction()
    {}

    public function completeRegistrationAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('register');
        }

        if (false === ($id = $this->_model->registerUser($request->getPost()))) {
            return $this->render('register');
        }
    }

    public function getLoginForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['login'] = $this->_model->getForm('login');
        $this->_forms['login']->setAction($urlHelper->url(array(
            'action' => 'authenticate'
        ), 'user'));
        $this->_forms['login']->setMethod('post');

        return $this->_forms['login'];
    }

    public function getRegistrationForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['register'] = $this->_model->getForm('register');
        $this->_forms['register']->setAction($urlHelper->url(array(
            'action' => 'complete-registration'
        ), 'user'));
        $this->_forms['register']->setMethod('post');

        return $this->_forms['register'];
    }

    public function getUserForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['userEdit'] = $this->_model->getForm('edit');
        $this->_forms['userEdit']->setAction($urlHelper->url(array(
            'action' => 'save'
        ), 'user'));
        $this->_forms['userEdit']->setMethod('post');

        return $this->_forms['userEdit'];
    }
}