<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 29.12.14
 * Time: 12:00
 */

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Debug\Debug;

class LoginController extends AbstractActionController
{
    protected $storage;
    protected $authservice;

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    public function logoutAction()
    {
        $this->getAuthService()->clearIdentity();
        return $this->redirect()->toRoute(NULL , array(
            'controller' => 'login',
            'action' =>  'index'
        ));
    }

    public function indexAction()
    {
        $form = $this->getServiceLocator()->get('LoginForm');
        $viewModel = new ViewModel(array('form' => $form));
        return $viewModel;
    }

    public function processAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->redirect()->toRoute(NULL , array(
                'controller' => 'login',
                'action' =>  'index'
            ));
        }

        $post = $request->getPost();

        $form = $this->getServiceLocator()->get('LoginForm');

        $form->setData($post);
        if (!$form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'form'  => $form,
            ));
            $model->setTemplate('users/login/index');
            return $model;
        } else {
            $adapter = $this->getAuthService()->getAdapter();
            //check authentication...
            $adapter->setIdentity($this->request->getPost('name'));
            $adapter->setCredential($this->request->getPost('password'));
            $result = $this->getAuthService()->authenticate();
            if ($result->isValid()) {
                $userInfo = $adapter->getResultRowObject(null, 'password');
                #$this->getAuthService()->getStorage()->write($this->request->getPost('name'));
                $this->getAuthService()->getStorage()->write($userInfo);
                return $this->redirect()->toRoute(NULL , array(
                    'controller' => 'login',
                    'action' =>  'confirm'
                ));
            } else {
                $model = new ViewModel(array(
                    'error' => true,
                    'form'  => $form,
                ));
                $model->setTemplate('users/login/index');
                return $model;
            }
        }
    }

    public function confirmAction()
    {
        $user = $this->getAuthService()->getStorage()->read();
        $viewModel  = new ViewModel(array(
            'user_name' => $user->name
        ));
        return $viewModel;
    }
}