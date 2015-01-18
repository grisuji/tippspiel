<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 17.01.15
 * Time: 11:47
 */

namespace Application\Controller;

use Application\Form\DeliverForm;
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
                'controller' => 'users/login',
                'action' => 'index'
            ));
        }
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $day = 1;
        } else {
            $day = $this->request->getPost('select');
        }

        $form = new DeliverForm($day);
        $matchTable = $this->getServiceLocator()->get('MatchTable');
        $matches = $matchTable->getUserMatchesByDay($userid, $day);

        $index = 0;
        $data = array();
        foreach($matches as $m) {
            $index++;
            $data['match'.$index.'_team1'] = $m->team1tip;
            $data['match'.$index.'_team2'] = $m->team2tip;
        }
        $form->populateValues($data);

        #Zend:Debug::dump($matches);
        #exit;
        $viewModel = new viewModel(array('matches' => $matches, 'form' => $form, 'preselect' => $day));
        return $viewModel;
    }


}