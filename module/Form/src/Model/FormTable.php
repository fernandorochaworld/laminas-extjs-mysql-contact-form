<?php

namespace Form\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;

class FormTable
{
    protected $tableGateway;

    function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        return $this->tableGateway->select();
    }

    public function saveData($form) {
        $data = [
            'name' => $form->getName(),
            'priority_id' => $form->getPriorityId(),
            'created_at' => $form->getCreatedAt(),
            'updated_at' => $form->getUpdatedAt(),
            'company' => $form->getCompany(),
            'email' => $form->getEmail(),
            'birthdate' => $form->getBirthdate(),
            'profession' => $form->getProfession(),
            'notes' => $form->getNotes(),
            'phone_home' => $form->getPhoneHome(),
            'phone_business' => $form->getPhoneBusiness(),
            'phone_mobile' => $form->getPhoneMobile(),
            'fax' => $form->getFax(),
            'biography' => $form->getBiography(),
        ];
        if ($form->getId()) {
            $this->tableGateway->update($data, ['id'=> $form->getId()]);
            return $form->getId();
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
