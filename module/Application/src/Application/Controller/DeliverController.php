<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 17.01.15
 * Time: 11:47
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Debug\Debug;

class DeliverController extends AbstractActionController {
    protected $storage;
    protected $authservice;

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    public function indexAction()
    {
        if($this->getAuthService()->hasIdentity()) {
            $userid = $this->getAuthService()->getStorage()->read()->id;
        } else {
            return $this->redirect()->toRoute(NULL, array(
                'controller' => 'login',
                'action' => 'index'
            ));
        }

        $matchTable = $this->getServiceLocator()->get('MatchTable');
        $matches = $matchTable->getUserMatchesByDay($userid, 17);
        #Zend:Debug::dump($matches);
        #exit;
        $viewModel = new viewModel(array('matches' => $matches));
        return $viewModel;
    }


}