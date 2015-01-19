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
    protected $day = 1;

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
        if ($request->isPost()) {
            $this->day = $this->request->getPost('select');
        }

        $matchTable = $this->getServiceLocator()->get('MatchTable');
        $matches = $matchTable->getUserMatchesByDay($userid, $this->day);

        $form = new DeliverForm($this->day);
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
        $viewModel = new viewModel(array('matches' => $matches, 'form' => $form, 'preselect' => $this->day));
        return $viewModel;
    }

    public function processAction()
    {
        if($this->getAuthService()->hasIdentity()) {
            $userid = $this->getAuthService()->getStorage()->read()->id;
        } else {
            return $this->redirect()->toRoute(NULL, array(
                'controller' => 'users/login',
                'action' => 'index'
            ));
        }

        $matchTable = $this->getServiceLocator()->get('MatchTable');
        $matches = $matchTable->getUserMatchesByDay($userid, $this->day);
        $tipTable = $this->getServiceLocator()->get('TipTable');

        $request = $this->getRequest();
        if (!$request->isPost()) {
            $index = 0;
            foreach($matches as $m) {
                $index++;
                $tip1 = $this->request->getPost('match'.$index.'_team1');
                $tip2 = $this->request->getPost('match'.$index.'_team2');
                if ($m->team1tip != $tip1 or $m->team2tip != $tip2)
                {
                    $m->team1tip = (isset($tip1)) ? $tip1 : 0;
                    $m->team2tip = (isset($tip2)) ? $tip2 : 0;
                    $tipTable->save($m);
                }
            }
        }
        return $this->redirect()->toRoute(NULL , array(
            'controller' => 'deliver',
            'action' =>  'index'
            )
        );
    }


}