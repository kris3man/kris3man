<?php
/**
 * AdminController.php
 *
 * Copyright (c) 2011 Shaun Freeman <shaun@shaunfreeman.co.uk>.
 *
 * This file is part of SF.
 *
 * SF is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SF is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SF.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   SF
 * @package    User
 * @subpackage Controller
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * Controller Class AdminController.
 *
 * @category   SF
 * @package    User
 * @subpackage Controller
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class User_AdminController extends Zend_Controller_Action
{
    public function init()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }

        $this->_model = new User_Model_User();
        $this->_authService = new ZendSF_Service_Authentication();

        $this->view->loginForm = $this->getLoginForm();
    }

    public function indexAction()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_forward('login');
            //throw new ZendSF_Exception('Access denied');
        }
    }

    public function loginAction()
    {}

     public function authenticateAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_redirect('admin');
        }

        // validate.
        $form = $this->_forms['login'];

        if (!$form->isValid($request->getPost())) {
            return $this->render('login');
        }

        $log = Zend_Registry::get('log');
        $log->info($form->getValues());

        if (false === $this->_authService->authenticate($form->getValues())) {
            $form->setDescription('Login failed, please try again.');
            return $this->render('login');
        }

        return $this->_redirect('admin');
    }

    public function getLoginForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['login'] = $this->_model->getForm('adminLogin');
        $this->_forms['login']->setAction($urlHelper->url(array(
            'module'        => 'user',
            'controller'    => 'admin',
            'action'        => 'authenticate',
            'isAdmin'       => true
        ), 'admin'));
        $this->_forms['login']->setMethod('post');

        return $this->_forms['login'];
    }
}