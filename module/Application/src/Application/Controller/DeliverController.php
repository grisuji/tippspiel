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
use DateTime;
use Zend\Debug\Debug;

class DeliverController extends AbstractActionController {
    protected $storage;
    protected $authservice;
    protected $day;

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    public function indexAction()
    {
        /* @var $matches \Application\Model\Match  */
        /* @var $m \Application\Model\Match  */
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
        $matchTable = $this->getServiceLocator()->get('MatchTable');

        if (is_null($this->day)) {
            $this->day = $matchTable->getDayOfNextMatch();
        }
        $matches = $matchTable->getUserMatchesByDay($userid, $this->day);
        $tipTable = $this->getServiceLocator()->get('TipTable');
        $index = 0;
        $data = array();
        $form = new DeliverForm($this->day);


        $now = new DateTime();
        $saved = 0; // How much tipps were saved
        foreach($matches as $m) {
            $index++;
            $start = new DateTime($m->start);
            if ($now->getTimestamp() > $start->getTimestamp()){
                $form->get('match' . $index . '_team1')->setAttribute('disabled', 'disabled');
                $form->get('match' . $index . '_team2')->setAttribute('disabled', 'disabled');
            } else {
                if ($post) {
                    $tip1 = $this->request->getPost('match' . $index . '_team1');
                    $tip2 = $this->request->getPost('match' . $index . '_team2');

                    if (($m->team1tip != $tip1 or $m->team2tip != $tip2) and (isset($tip1) or isset($tip2))) {
                        if ($tip1!="") $m->team1tip = $tip1;
                        if ($tip2!="") $m->team2tip = $tip2;
                        $m->userid = $userid;
                        $tipTable->saveTip($m);
                        $saved++;
                    }
                }
            }
            $data['match'.$index.'_team1'] = $m->team1tip;
            $data['match'.$index.'_team2'] = $m->team2tip;
        }
        $form->populateValues($data);
        $viewModel = new viewModel(array('matches' => $matches, 'form' => $form, 'savedtips' => $saved));
        #Debug::dump($this->day);exit;
        return $viewModel;
    }


}