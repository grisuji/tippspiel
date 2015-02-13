<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 02.01.15
 * Time: 10:25
 */

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserManagerController extends AbstractActionController{

    protected $authservice;

    public function getAuthService()
    {
        if (! $this->authservice) {
            //$this->authservice = new AuthenticationService();
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    public function indexAction()
    {
        if($this->getAuthService()->getStorage()->read()->name!='admin'){
        //if(!($this->getAuthService()->hasIdentity() and $this->getAuthService()->getIdentity()=='admin')) {
            return $this->redirect()->toRoute(NULL, array(
                'controller' => 'login',
                'action' => 'index'
            ));
        }
        $userTable = $this->getServiceLocator()->get('UserTable');
        $viewModel = new viewModel(array('users' => $userTable->fetchAll()));
        return $viewModel;
    }


    /**
     * @return ViewModel
     */
    public function editAction()
    {
        $userTable = $this->getServiceLocator()->get('UserTable');
        $user = $userTable->getUser($this->params()->fromRoute('id'));
        unset($user->password);
        $form = $this->getServiceLocator()->get('UserEditForm');
        $form->bind($user);
        $viewModel = new viewModel(array(
            'form' => $form,
            'user_id' => $this->params()->fromRoute('id'),
        ));
        return $viewModel;
    }

    public function processAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('users/user-manager', array('action' => 'edit'));
        }

        // Get Id from Post
        $post = $this->request->getPost();
        $userTable = $this->getServiceLocator()->get('UserTable');
        $user = $userTable->getUser($post->id);
        unset($user->password);
        //  bind User to Form
        $form = $this->getServiceLocator()->get('UserEditForm');
        $form->bind($user);
        $form->setData($post);
        if (!$form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'form'  => $form,
            ));
            $model->setTemplate('users/user-manager/edit');
            return $model;
        }

        // save User
        $this->getServiceLocator()->get('UserTable')->saveUser($user);
        // redirect
        $referer = $this->getRequest()->getHeader('Referer');
        if ($referer) {
            $refererUrl = $referer->uri()->getPath(); // referer url
            $refererHost = $referer->uri()->getHost(); // referer host
            $host = $this->getRequest()->getUri()->getHost(); // current host

            // only redirect to previous page if request comes from same host
            if ($refererUrl && ($refererHost == $host)) {
                return $this->redirect()->toUrl($refererUrl);
            }
        }
        // redirect to home if no referer or from another page
        return $this->redirect()->toRoute('users/user-manager');
    }

    public function deleteAction()
    {
        $this->getServiceLocator()->get('UserTable')->deleteUser($this->params()->fromRoute('id'));
    }
}