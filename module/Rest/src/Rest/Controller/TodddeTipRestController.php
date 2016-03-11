<?php
namespace Rest\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractRestfulController;

use Zend\View\Model\JsonModel;

class TodddeTipRestController extends AbstractRestfulController
{

    protected $todddeTipTable;

    private function getRawTodddeTipTable()
    {
        if (!$this->todddeTipTable) {
            $this->todddeTipTable = $this->getServiceLocator()->get('RawTodddeTipTable');
        }
        return $this->todddeTipTable;
    }

    private function genJSon($db_result) {
        $result = array();
        foreach ($db_result as $r) {
            $result[] = $r;
        }

        return new JsonModel(array(
            'data' => $result
        ));
    }

    public function getList()
    {
        $tips = $this->getRawTodddeTipTable()->fetchAll();
        return $this->genJSon($tips);
    }

    public function get($id)
    {
        $tips = $this->getRawTodddeTipTable()->getNewTodddeTip($id);
        return $this->genJSon($tips);
    }

    public function create($data)
    {
        exit;
    }

    public function update($id, $data)
    {
        exit;
    }

    public function delete($id)
    {
        exit;
    }

}

