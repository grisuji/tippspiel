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
use Zend\Json\Expr;
use Zend\Json\Json;
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
        $matches_live = $matchTable->getUserMatchesByDay("2015", $userid, $day);
        $matches_stats = $matchTable->getSaisonTipsAndMatches(2015);
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
        $wonbytips = $this->getHighChartPointMatrix($saison->getPointMatrix($day, true, true)[$userid]["data"], "Gewonnene Punkte über Tipps");
        $lostbytips = $this->getHighChartPointMatrix($saison->getPointMatrix($day, false, true)[$userid]["data"], "Verlorene Punkte über Tipps", "#FF0000");
        $wonbyresult = $this->getHighChartPointMatrix($saison->getPointMatrix($day, true, false)[$userid]["data"], "Gewonnene Punkte über Ergebnisse");
        $lostbyresult = $this->getHighChartPointMatrix($saison->getPointMatrix($day, false, false)[$userid]["data"], "Verlorene Punkte über Ergebnisse", "#FF0000");
        $viewModel = new viewModel(array('live' => $matches_live
            , 'user' => $user
            , 'form' => $form
            , 'userinfo' => $userinfo
            , 'hc_tip_won_diagram' => Json::encode($wonbytips, false, array('enableJsonExprFinder' => true))
            , 'hc_tip_lost_diagram' => Json::encode($lostbytips, false, array('enableJsonExprFinder' => true))
            , 'hc_res_won_diagram' => Json::encode($wonbyresult, false, array('enableJsonExprFinder' => true))
            , 'hc_res_lost_diagram' => Json::encode($lostbyresult, false, array('enableJsonExprFinder' => true))

        ));
        return $viewModel;

    }

    private function getHighChartPointMatrix($matrix, $title, $color="#00FF00"){
        $linechart = array();
        $linechart['chart'] = array(
            'type' => 'heatmap',
            'marginTop' => 40,
            'marginBottom' => 40,
            'inverted' => true,
            'renderTo' => 'container'
        );
        $linechart['title'] = array(
            'text' => $title
        );
        $linechart['xAxis'] = array(
            'categories' => array("0", "1", "2", "3", "4", "5", ">5")
        );
        $linechart['yAxis'] = array(
            'categories' => array("0", "1", "2", "3", "4", "5", ">5")
        );
        $linechart['colorAxis'] = array(
            'min' => 0,
            'minColor' => "#FFFFFF",
            'maxColor' => $color
        );
        $linechart['legend'] = array(
            'align' => "right",
            'layout' => "vertical",
            'margin' => 0,
            'verticalAlign' => "top",
            'y' => 25,
            'symbolHeight' => 280
        );
        $linechart["tooltip"] = array(
            'formatter' => new Expr('function () { return "Bei <b>"+ this.point.x +":"+ this.point.y +"</b><br/>waren es " + this.point.value + " Punkte." ;}')
        );
        $linechart['series'] = array(
            array(
                'name' => $title,
                'borderWidth' => 1,
                'data' => $matrix,
                'dataLabels' => array(
                    'enabled' => true,
                    'color' => '#000000'
                )
            )
        );
        return $linechart;
    }


}

/*
 * {
    "chart": {        type: "heatmap",        marginTop: 40,        marginBottom: 80        },
    title: {        text: "Gewonnene Punkte bei Tipps"        },
    xAxis: {        categories: ["0", "1", "2", "3", "4", "5", ">5"]    },
    yAxis: {        categories: ["0", "1", "2", "3", "4", "5", ">5"],        title: null    },
    colorAxis: {        min: 0,        minColor: "#FFFFFF",        maxColor: "#00FF00", gridLineColor:  "#FF0000"   },
    legend: {        align: "right",        layout: "vertical",        margin: 0,        verticalAlign: "top",        y: 25,        symbolHeight: 280    },
    tooltip:{formatter: function () { return "Bei <b>"+ this.point.x +":"+ this.point.y +"</b> Tipps <br/>gingen " + this.point.value + " Punkte<br/>verloren." ;}},
    series: [{
        name: "Gewonnene Punkte bei Tipp",
        borderWidth: 1,
        data: '.$this->hc_tip_won_data.',
        dataLabels: {
                enabled: true,
                color: "#000000"
                }
        }]
        }
 */