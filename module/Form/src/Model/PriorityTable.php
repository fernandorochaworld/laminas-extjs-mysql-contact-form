<?php

namespace Form\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;

class PriorityTable
{
    protected $tableGateway;

    function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        return $this->tableGateway->select();
    }

    public function saveData($priority) {
        $data = [
            'name' => $priority->getName(),
        ];
        if ($priority->getId()) {
            $this->tableGateway->update($data, ['id'=> $priority->getId()]);
            return $priority->getId();
        } else {
            $this->tableGateway->insert($data);
            return $this->tableGateway->getLastInsertValue();
        }
    }

    public function getData($id) {
        return $this->tableGateway->select(['id'=>$id])->current();
    }

    public function deleteData($id) {
        return $this->tableGateway->delete(['id'=>$id]);
    }

}
