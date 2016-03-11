<?php
namespace Rest\Controller;

use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractRestfulController;

use Zend\View\Model\JsonModel;

class TodddeUserMappingRestController extends AbstractRestfulController
{

    protected $todddeUserMappingTable;

    private function getRawTodddeUserMappingTable()
    {
        if (!$this->todddeUserMappingTable) {
            $this->todddeUserMappingTable = $this->getServiceLocator()->get('RawTodddeUserMappingTable');
        }
        return $this->todddeUserMappingTable;
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
        $mappings = $this->getRawTodddeUserMappingTable()->fetchAll();
        return $this->genJSon($mappings);
    }

    public function get($id)
    {
        $mappings = $this->getRawTodddeUserMappingTable()->getNewTodddeUserMapping($id);
        return $this->genJSon($mappings);
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

