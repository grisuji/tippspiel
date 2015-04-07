<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 24.01.15
 * Time: 12:53
 */

namespace Application\Controller;

use Application\Form\UserpointsForm;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Model\Saison;
use Zend\View\Model\ViewModel;
use DateTime;

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
            $userid = $this->request->getPost('selected_userid');
            $day = $this->request->getPost('selected_day');
            /* get the values from hidden-fields */
            if (empty($day)) {
                $day = $this->request->getPost('hidden_day');
            }
            if (empty($userid)) {
                $userid = $this->request->getPost('hidden_userid');
            }
        } else {
            $userid = 2;
            $day = 1;
        }
        /* now redirect */
        return $this->redirect()->toRoute('application/userpoints' , array(
            'action' =>  'index',
            'day' => $day,
            'userid' => $userid
        ));
    }


    public function indexAction()
    {
        $userid = $this->getEvent()->getRouteMatch()->getParam('userid');
        $day = $this->getEvent()->getRouteMatch()->getParam('day');

        $logged_in_id = 0;
        if($this->getAuthService()->hasIdentity()) {
            $logged_in_id = $this->getAuthService()->getStorage()->read()->id;
        }

        if (empty($userid)) {
            if ($logged_in_id > 1) {
                $userid = $logged_in_id;
            }
             else {
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
        $matches_live = $matchTable->getUserMatchesByDay(2014, $userid, $day);
        $matches_stats = $matchTable->getSaisonTipsAndMatches(2014);
        $userTable = $this->getServiceLocator()->get('UserTable');
        $userinfo = $userTable->getUser($userid);
        $users = $userTable->fetchAll();
        $userlist = array();
        foreach($users as $u) {
            if ($u->id > 1) { #skip admin
                $userlist[$u->id] = $u->name;
            }
        }
        $form = new UserpointsForm($day, $userid, $userlist);

        $saison = new Saison();
        $now = new DateTime();

        #for the livedata
        foreach ($matches_live as $m) {
            $start = new DateTime($m->start);
            if ($now->getTimestamp() <= $start->getTimestamp() and $userid != $logged_in_id) {
                $m->team1tip = "";
                $m->team2tip = "";
            }
            $m->setPoints();
        }

        # for the statistic
        foreach ($matches_stats as $m) {
            if ($m->userid < 2) continue;
            if ($m->userid != $userid) continue;
            # hide all future tips
            $start = new DateTime($m->start);
            if ($now->getTimestamp() <= $start->getTimestamp() and $userid != $logged_in_id) {
                $m->team1tip = "-";
                $m->team2tip = "-";
            }
            $saison->addMatch($m);
        }
        $saison->fillMissingDays();
        $saison->setPoints();

        $live = $saison->getMatchDataByDay($day);
        $user = $saison->getUserDataByDay($day)[$userid];
        #Debug::dump(json_encode($missing_point_data));
        #Debug::dump(json_encode($won_point_data));
        #Debug::dump($user);
        $viewModel = new viewModel(array('live' => $matches_live
            , 'user' => $user
            , 'form' => $form
            , 'userinfo' => $userinfo
            , 'hc_tip_won_data' => json_encode($saison->getHighChartTipPoints($day,true)[$userid]["data"])
            , 'hc_tip_lost_data' => json_encode($saison->getHighChartTipPoints($day,false)[$userid]["data"])
            , 'hc_res_won_data' => json_encode($saison->getHighChartResultPoints($day,true)[$userid]["data"])
            , 'hc_res_lost_data' => json_encode($saison->getHighChartResultPoints($day,false)[$userid]["data"])

        ));
        return $viewModel;

    }


}
