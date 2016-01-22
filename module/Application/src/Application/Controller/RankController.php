<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 26.01.15
 * Time: 20:14
 */

namespace Application\Controller;
use Application\Model\Saison;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DateTime;
use Zend\Json\Expr;
use Zend\Json\Json;
use Zend\Debug\Debug;


class RankController  extends AbstractActionController{

    private static function cmp_points($a, $b) {
        if ($a['rank'] == $b['rank']) {
            return RankController::cmp_name($a, $b);
        } else {
            return ($a['rank'] < $b['rank']) ? -1 : 1;
        }
    }

    private static function cmp_name($a, $b) {
        $n1 = strtoupper($a['name']);
        $n2 = strtoupper($b['name']);
        if ($n1 == $n2) {
            return 0;
        } else {
            return ($n1 < $n2) ? -1 : 1;
        }
    }

    public function indexAction(){
        /* @var $m \Application\Model\Match  */
        /* @var $matchTable \Application\Model\MatchTable  */
        /* @var $u \Users\Model\User */
        /* @var $userTable \Users\Model\UserTable  */

        $matchTable = $this->getServiceLocator()->get('MatchTable');
        $day = $matchTable->getDayOfNextMatch();
        $matches = $matchTable->getSaisonTipsAndMatches(2015);
        $toddde =  $matchTable->getSaisonTodddeTipsAndMatches(2015);

        $saison = new Saison();
        $now = new DateTime();
        foreach(array($matches, $toddde) as $set){
            foreach($set as $m) {
                if ($m->userid < 2) continue;
                if ($saison->isMatchSet($m)) continue;
                # hide all future tips
                $start = new DateTime($m->start);
                if ($now->getTimestamp() <= $start->getTimestamp()) {
                    $m->team1tip = "-";
                    $m->team2tip = "-";
                }
                $saison->addMatch($m);
            }
        }


        $saison->fillMissingDays();
        $saison->setPoints();
        $saison->sortAllDays();
        $live = $saison->getMatchDataByDay($day);
        $user = $saison->getUserDataByDay($day);
        usort($user, array($this, "cmp_points"));
        #Debug::dump($live );
        #Debug::dump($user);
        #Debug::dump($saison->getDays($day));
        #Debug::dump("---");
        #Debug::dump($saison->getHighchartUserRanks($day));
        $hc_yaxis_data = $saison->getHighchartUserRanks($day);
        $hc_xaxis_data = $saison->getDays($day);
        #Debug::dump($hc_xaxis_data);
        #Debug::dump($hc_yaxis_data);

        $data = $this->getHighChartLine("2015", $hc_xaxis_data, $hc_yaxis_data);
        $diagram = Json::encode($data, false, array('enableJsonExprFinder' => true));
        $view = new ViewModel(
            array(
                'user'=>$user,
                'day'=>$day,
                'live'=>$live,
                'diagram'=>$diagram
            ));

        return $view;
    }

    private function getHighChartLine($saison, $xdata, $ydata){
        $linechart = array();
        $linechart['chart'] = array(
            'type' => 'line',
            'zoomType' => "x"
        );
        $linechart["title"] = array(
            'text' => "Platzierungen pro Spieltag",
            'x' => -20
        );
        $linechart["subtitle"] = array(
            'text' => "Saison " . $saison,
            'x' => -20
        );
        $linechart["xAxis"] = array(
            'categories' => $xdata
        );
        $linechart["yAxis"] = array(
            'title' => array(
                'text' => "Platzierung"
            ),
            'type' => 'linear',
            'reversed' => true,
            'plotlines' => array(array(
                'value' => 0,
                'width' => 1,
                'color' => "#808080"
            ))
        );
        $linechart["plotOptions"] = array(
            'column' => array(
                #'stacking' => "percent",
                'stacking' => "normal",
                'pointPadding' => 0.2,
                'borderWidth' => 0
            )
        );
        $linechart["tooltip"] = array(
            'valueSuffix' => ' Punkte',
            'formatter' => new Expr('function () { return "<b>" + this.series.name + "</b><br/>Platz: "+ this.y +"<br/>Punkte: " + this.point.z;}')
        );
        $linechart["legend"] = array(
            'layout' => "horizontal",
            'align' => "center",
            'verticalAlign' => "bottom",
            'borderWidth' => 0
        );
        $linechart["series"] = $ydata;
#        Debug::dump(json_encode($linechart));
        return $linechart;
    }

}

/*
 *
   {
     "chart":{"type":"line","zoomType":"x"},
     "title":{"text":"Der Platzierungsverlauf während der Saison","x":-20},
     "subtitle":{"text":"Saison 2014","x":-20},
     "xAxis":{"categories":'.$this->hc_xaxis_data.'},
     "yAxis":{"reversed":true,"title":{"text":"Platzierungen"},"type":"linear","plotlines":[{"value":0,"width":1,"color":"#808080"}]},
     "tooltip":{"valueSuffix":". Platz"},
     "legend":{"layout":"horizontal","align":"center","verticalAlign":"bottom","borderWidth":0},
     tooltip:{formatter: function () { return this.series.name + "<br/>Spieltag: "+ this.x +"<br/>Platz: "+ this.y +"<br/>Punkte: " + this.point.z;}},
     "series":'.$this->hc_yaxis_data.'
    }
 */