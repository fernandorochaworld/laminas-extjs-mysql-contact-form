<?php

namespace Form\Model;

use Laminas\Crypt\Password\Bcrypt;
use Laminas\Db\TableGateway\TableGatewayInterface;

class UserTable
{
    protected $table = 'users';
    protected $tableGateway;

    function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getTable() {
        return $this->table;
    }

    public function fetchAll() {
        return $this->tableGateway->select();
    }

    public function saveData($user) {
        $data = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
        ];
        if ($user->getPassword()) {
            $data['password'] = (new Bcrypt())->create($user->getPassword());
        }
        if ($user->getId()) {
            $this->tableGateway->update($data, ['id'=> $user->getId()]);
            return $user->getId();
        } else {
            $this->tableGateway->insert($data);
            return $this->tableGateway->getLastInsertValue();
        }
    }

    public function getData($id) {
        return $this->tableGateway->select(['id'=>$id])->current();
    }

    public function fetchAccountByEmail($email) {
        return $this->tableGateway->select(['email'=>$email])->current();
    }

    public function deleteData($id) {
        return $this->tableGateway->delete(['id'=>$id]);
    }

}
