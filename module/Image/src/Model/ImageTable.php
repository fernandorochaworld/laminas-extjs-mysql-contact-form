<?php

namespace Image\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;

class ImageTable
{
    protected $tableGateway;

    function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        return $this->tableGateway->select('deleted_at is null');
    }

    public function saveData($image) {
        $data = [
            'name' => $image->getName(),
            'image' => $image->getImage(),
            'created_at' => $image->getCreatedAt(),
            'updated_at' => $image->getUpdatedAt(),
        ];
        if ($image->getId()) {
            $this->tableGateway->update($data, ['id'=> $image->getId()]);
        } else {
            $this->tableGateway->insert($data);
        }
    }

    public function getImage($id) {
        return $this->tableGateway->select(['id'=>$id])->current();
    }

    public function deleteImage($id) {
        return $this->tableGateway->delete(['id'=>$id]);
    }

}
