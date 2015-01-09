<?php

namespace Users\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class IndexController extends AbstractActionController {
    public function indexAction(){
        return $this->redirect()->toRoute('users/login' , array(
            'controller' => 'login',
            'action' =>  'index'
        ));
    }

    public function registerAction(){
        $view = new ViewModel();
        $view->setTemplate('users/register');
        return $view;
    }

    public function loginAction(){
        $view = new ViewModel();
        $view->setTemplate('users/login');
        return $view;
    }
} 