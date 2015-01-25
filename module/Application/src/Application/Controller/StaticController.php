<?php
/**
 * Created by PhpStorm.
 * User: grisuji
 * Date: 25.01.15
 * Time: 19:47
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class StaticController extends AbstractActionController {

    public function indexAction() {
        return new viewModel();
    }

    public function impressumAction(){
        $view = new viewModel();
        return $view;
    }

    public function rulesAction(){
        return new viewModel();
    }


}