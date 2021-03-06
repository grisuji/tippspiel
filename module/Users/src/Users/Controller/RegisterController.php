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

use Users\Model\User;
use Users\Form\RegisterForm;

class RegisterController extends AbstractActionController {
    public function indexAction()
    {
        $form = new RegisterForm();
        $viewModel = new ViewModel(array('form' => $form));
        return $viewModel;
    }

    public function processAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->redirect()->toRoute(NULL , array(
                'controller' => 'register',
                'action' =>  'index'
            ));
        }

        $post = $request->getPost();

        $form = $this->getServiceLocator()->get('RegisterForm');
        $form->setData($post);
        if (!$form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'type' => 'default',
                'form'  => $form,
            ));
            $model->setTemplate('users/register/index');
            return $model;
        }

        // Create user
        if (!$this->createUser($form->getData())) {
            $model = new ViewModel(array(
                'error' => true,
                'type' => 'username',
                'form'  => $form,
            ));
            $model->setTemplate('users/register/index');
            return $model;
        }
        return $this->redirect()->toRoute(NULL , array(
            'controller' => 'register',
            'action' =>  'confirm'
        ));
    }

    public function confirmAction()
    {
        $viewModel = new ViewModel();
        return $viewModel;
    }

    protected function createUser(array $data)
    {
        $user = new User();
        $user->exchangeArray($data);
        $user->registerdate = date("Y-m-d H:i:s",time());

        /* @var $userTable  \Users\Model\UserTable  */
        $userTable = $this->getServiceLocator()->get('UserTable');
        try {
            $userTable->getUserByName($user->name);
            return false;
        } catch (\Exception $e) {
            $userTable->saveUser($user);
        }

        return true;
    }
}