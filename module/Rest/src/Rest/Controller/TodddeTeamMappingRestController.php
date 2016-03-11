<?php
namespace Rest\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractRestfulController;

use Zend\View\Model\JsonModel;

class TodddeTeamMappingRestController extends AbstractRestfulController
{

    protected $todddeTeamMappingTable;

    private function getRawTodddeTeamMappingTable()
    {
        if (!$this->todddeTeamMappingTable) {
            $this->todddeTeamMappingTable = $this->getServiceLocator()->get('RawTodddeTeamMappingTable');
        }
        return $this->todddeTeamMappingTable;
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
        $teammapping = $this->getRawTodddeTeamMappingTable()->fetchAll();
        return $this->genJSon($teammapping);
    }

    public function get($id)
    {
        $teammapping = $this->getRawTodddeTeamMappingTable()->getNewTodddeTeamMapping($id);
        return $this->genJSon($teammapping);
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

