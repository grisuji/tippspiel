<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 26.01.15
 * Time: 20:14
 */

namespace Application\Controller;
use Application\Rules\GrisujiPoints;
use Application\Rules\ToddePoints;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DateTime;
use Zend\Debug\Debug;


class RankController  extends AbstractActionController{

    private static function cmp_points($a, $b) {
        if ($a['points'] == $b['points']) {
            return RankController::cmp_name($a, $b);
        } else {
            return ($a['points'] > $b['points']) ? -1 : 1;
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

        $pointhelper = new GrisujiPoints();
        $toddehelper = new ToddePoints();

        /* @var $m \Application\Model\Match  */
        /* @var $matchTable \Application\Model\MatchTable  */
        /* @var $u \Users\Model\User */
        /* @var $userTable \Users\Model\UserTable  */


        $matchTable = $this->getServiceLocator()->get('MatchTable');
        $day = $matchTable->getDayOfNextMatch();
        $matches = $matchTable->getSaisonTipsAndMatches(2014);
        $user = array();
        $now = new DateTime();
        $live = array();
        $live['matches'] = array();
        $live['emblem1'] = array();
        $live['emblem2'] = array();
        $live['goals1'] = array();
        $live['goals2'] = array();
        $live['finished'] = array();
        foreach($matches as $m) {
            if ($m->userid < 2) continue;

            if (!isset($user[$m->userid]))
            {
                $user[$m->userid] = array('name' => $m->username,
                                        'points' => 0,
                                        'todde' => 0,
                                        'todde_day' => 0,
                                        'tip1' => array(),
                                        'tip2' => array(),
                                        'matchpoints' => array(),
                                        $day => 0,
                                        'id' => $m->userid);
            }
            $points = $pointhelper->getPoints($m->team1goals, $m->team2goals, $m->team1tip, $m->team2tip);
            $todde = $toddehelper->getPoints($m->team1goals, $m->team2goals, $m->team1tip, $m->team2tip);

            $user[$m->userid][$m->day] += $points;
            $user[$m->userid]['points'] += $points;
            $user[$m->userid]['todde'] += $todde;

            if ($day == $m->day) {
                array_push($live['matches'], $m->id);
                $live['emblem1'][$m->id] = $m->team1emblem;
                $live['emblem2'][$m->id] = $m->team2emblem;
                $start = new DateTime($m->start);
                if ($now->getTimestamp() <= $start->getTimestamp()) {
                    $m->team1tip = "-";
                    $m->team2tip = "-";
                } else {
                    $live['goals1'][$m->id] = $m->team1goals;
                    $live['goals2'][$m->id] = $m->team2goals;
                    $user[$m->userid]['matchpoints'][$m->id] = $points;
                    $live['finished'][$m->id] = $m->isfinished;
                }
                $user[$m->userid]['tip1'][$m->id] =  $m->team1tip;
                $user[$m->userid]['tip2'][$m->id] =  $m->team2tip;
                $user[$m->userid]['todde_day'] += $todde;
            }
        }
        $live['matches'] = array_unique($live['matches']);
        usort($user, array($this, "cmp_points"));
        $jsons = array(
            "linechart" => json_encode($this->genHighChartLine(2014, $user, $day))
        );
        $view = new ViewModel(array('user'=>$user, 'day'=>$day, 'live'=>$live, 'json'=>$jsons));

        return $view;
    }

    private function genHighChartLine($saison, $userdata, $day){

        $minday=36;
        # find the first day with non-zero-points
        foreach ($userdata as $id=>$u) {
            for ($actday = 1; $actday <= $day; $actday++) {
                if ($u[$actday] > 0) {
                    $minday = min($minday, $actday);
                    break 2;
                }
            }
        }
                # create some arrays with data
        $spieltage=array();
        for ($d=$minday; $d <= $day; $d++) {
            array_push($spieltage, $d);
        }
        $linechart = array();
        $linechart['chart'] = array(
            'type' => 'column',
            'zoomType' => "x"
        );
        $linechart["title"] = array(
            'text' => "Entwicklung der Spielerpunkte",
            'x' => -20
        );

        $linechart["subtitle"] = array(
            'text' => "Saison " . $saison,
            'x' => -20
        );

        $linechart["xAxis"] = array(
            'categories' => $spieltage
        );

        $linechart["yAxis"] = array(
            'title' => array(
                'text' => "Gesamtpunkte"
            ),
            'type' => 'linear',
            'plotlines' => array(array(
                'value' => 0,
                'width' => 1,
                'color' => "#808080"
            ))
        );

        $linechart["plotOptions"] = array(
            'column' => array(
                'stacking' => "percent",
                'pointPadding' => 0.2,
                'borderWidth' => 0
            )
        );

        $linechart["tooltip"] = array(
            'valueSuffix' => ' Punkte'
        );

        $linechart["legend"] = array(
            'layout' => "horizontal",
            'align' => "center",
            'verticalAlign' => "bottom",
            'borderWidth' => 0
        );
        $linechart["series"] = array(

        );

        foreach ($userdata as $id=>$u) {
            $data = array_fill(0, 1+$day-$minday, 0);
            for ($actday=$minday; $actday<=$day; $actday++) {
                    $data[$actday-$minday] = $u[$actday];
            }
            $new_user=array(
                'name' => $u['name'],
                'data' => $data
            );
            array_push($linechart["series"], $new_user);
        }
#        Debug::dump(json_encode($linechart));
        return $linechart;
    }

    private function genHighChartCumLine($saison, $userdata, $day){

        $minday=36;
        # find the first day with non-zero-points
        foreach ($userdata as $id=>$u) {
            for ($actday = 1; $actday <= $day; $actday++) {
                if ($u[$actday] > 0) {
                    $minday = min($minday, $actday);
                    break 2;
                }
            }
        }
        # create some arrays with data
        $spieltage=array();
        for ($d=$minday; $d <= $day; $d++) {
            array_push($spieltage, $d);
        }
        $linechart = array();
        $linechart['chart'] = array(
            'type' => 'line',
            'zoomType' => "x"
        );
        $linechart["title"] = array(
            'text' => "Entwicklung der Spielerpunkte",
            'x' => -20
        );

        $linechart["subtitle"] = array(
            'text' => "Saison " . $saison,
            'x' => -20
        );

        $linechart["xAxis"] = array(
            'categories' => $spieltage
        );

        $linechart["yAxis"] = array(
            'title' => array(
                'text' => "Gesamtpunkte"
            ),
            'type' => 'linear',
            'plotlines' => array(array(
                'value' => 0,
                'width' => 1,
                'color' => "#808080"
            ))
        );

        $linechart["tooltip"] = array(
            'valueSuffix' => ' Punkte'
        );

        $linechart["legend"] = array(
            'layout' => "horizontal",
            'align' => "center",
            'verticalAlign' => "bottom",
            'borderWidth' => 0
        );
        $linechart["series"] = array(

        );

        foreach ($userdata as $id=>$u) {
            $data = array_fill(0, 1+$day-$minday, 0);
            for ($actday=$minday; $actday<=$day; $actday++) {
                for ($d=$actday; $d<=$day; $d++) {
                    $data[$d-$minday] += $u[$actday];
                }
            }
            $new_user=array(
                'name' => $u['name'],
                'data' => $data
            );
            array_push($linechart["series"], $new_user);
        }
#        Debug::dump(json_encode($linechart));
        return $linechart;
    }
}