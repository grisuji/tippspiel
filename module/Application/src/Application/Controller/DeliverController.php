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

    public function processAction()
    {
        /* @var $matches \Application\Model\Match  */
        /* @var $matchTable \Application\Model\MatchTable  */
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
        $viewModel = new viewModel(array('matches' => $matches, 'form' => $form));
        return $viewModel;
    }

    public function indexAction()
    {
        /* @var $matches \Application\Model\Match  */
        /* @var $tipTable \Application\Model\TipTable  */
        /* @var $matchTable \Application\Model\MatchTable  */
        if($this->getAuthService()->hasIdentity()) {
            $userid = $this->getAuthService()->getStorage()->read()->id;
        } else {
            return $this->redirect()->toRoute(NULL, array(
                'controller' => 'users/login',
                'action' => 'index'
            ));
        }

        $request = $this->getRequest();
        $post = $request->isPost();
        if ($post) {
            $this->day = $this->request->getPost('select');
            if (is_null($this->day)) {
                $this->day = $this->request->getPost('day');
#                Debug::dump($this->day);exit;
            }
        }

        if (is_null($this->day)) {
            $this->day = 1;
        }
        $matchTable = $this->getServiceLocator()->get('MatchTable');
        $matches = $matchTable->getUserMatchesByDay($userid, $this->day);
        $tipTable = $this->getServiceLocator()->get('TipTable');

        $index = 0;
        $data = array();
        foreach($matches as $m) {
            $index++;
            if ($post) {
                $tip1 = $this->request->getPost('match' . $index . '_team1');
                $tip2 = $this->request->getPost('match' . $index . '_team2');

                if ($m->team1tip != $tip1 or $m->team1tip != $tip2) {
                    if (!empty($tip1)) $m->team1tip = $tip1;
                    if (!empty($tip2)) $m->team2tip = $tip2;
                    $m->userid = $userid;
                    $tipTable->saveTip($m);
                }
            }
            $data['match'.$index.'_team1'] = $m->team1tip;
            $data['match'.$index.'_team2'] = $m->team2tip;
        }
        $form = new DeliverForm($this->day);
        $form->populateValues($data);
        $viewModel = new viewModel(array('matches' => $matches, 'form' => $form));
        #Debug::dump($this->day);exit;
        return $viewModel;
    }


}