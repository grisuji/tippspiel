<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 24.01.15
 * Time: 12:53
 */

namespace Application\Controller;

use Application\Form\UserpointsForm;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Model\GrisujiPoints;
use Zend\View\Model\ViewModel;

class UserpointsController extends AbstractActionController{
    protected $authservice;
    protected $day;

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

    public function processAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $userid = $this->request->getPost('selectuserid');
            $day = $this->request->getPost('selectday');
            /* get the values from hidden-fields */
            if (empty($day)) {
                $day = $this->request->getPost('hiddenday');
            }
            if (empty($userid)) {
                $userid = $this->request->getPost('hiddenuserid');
            }
        } else {
            $userid = 2;
            $day = 1;
        }
        /* now redirect */
        return $this->redirect()->toRoute(NULL , array(
            'controller' => 'userpoints',
            'action' =>  'index',
            'day' => $day,
            'userid' => $userid
        ));
    }

    public function indexAction()
    {
        $pointhelper = new GrisujiPoints();
        $userid = $this->getEvent()->getRouteMatch()->getParam('userid');
        $day = $this->getEvent()->getRouteMatch()->getParam('day');
        if (empty($userid)) {
            if($this->getAuthService()->hasIdentity()) {
                $userid = $this->getAuthService()->getStorage()->read()->id;
            } else {
                $userid = 2;
            }
        }
        /* @var $m \Application\Model\Match  */
        /* @var $matchTable \Application\Model\MatchTable  */
        /* @var $u \Users\Model\User */
        /* @var $userTable \Users\Model\UserTable  */

        $matchTable = $this->getServiceLocator()->get('MatchTable');
        if (empty($day)) {
            $day = $matchTable->getDayOfNextMatch();
        }

        $matches = $matchTable->getUserMatchesByDay($userid, $day);
        $userTable = $this->getServiceLocator()->get('UserTable');
        $users = $userTable->fetchAll();
        $userlist = array();
        foreach($users as $u) {
            $userlist[$u->id] = $u->name;
        }
        $form = new UserpointsForm($day, $userid, $userlist);
        foreach ($matches as $m) {
            $m->points = $pointhelper->getPoints($m->team1goals, $m->team2goals, $m->team1tip, $m->team2tip);
        }
        $viewModel = new viewModel(array('matches' => $matches, 'form' => $form));
        return $viewModel;

    }


}
