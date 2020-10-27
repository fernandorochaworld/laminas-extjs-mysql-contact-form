<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Form\Controller;

use Exception;
use Form\Model\Form;
use Form\Model\User;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use RuntimeException;

class UserController extends AbstractActionController
{
    protected $table;
    protected $authenticationService;
    private const CREDENTIAL_MESSAGE = 'No credencials.';

    public function __construct($authenticationService, $table)
    {
		$this->authenticationService = $authenticationService;
        $this->table = $table;
    }

    private function redirectToLogin() {
        return $this->redirect()->toRoute('login');
    }

    public function homeAction()
    {
		if (!$this->authenticationService->hasIdentity()) {
			return $this->redirectToLogin();
        }
        return new ViewModel();
    }

    public function indexAction()
    {
		if (!$this->authenticationService->hasIdentity()) {
            throw new RuntimeException(self::CREDENTIAL_MESSAGE);
        }
        $request = $this->getRequest();
        if ($request->isGet()){
            $id = (int) $this->params()->fromRoute('id', 0);
            if ($id) {
                return $this->show($id);
            } else {
                return $this->load();
            }
        } elseif ($request->isPut() || $request->isPost()){
            return $this->save();
        } elseif ($request->isDelete()){
            return $this->delete();
        }
    }

    private function load()
    {
        /* @var $users AbstractResultSet */
        $users = $this->table->fetchAll();
        return new JsonModel($users);
    }

    private function save()
    {
        $request = $this->getRequest();
        $params = json_decode($request->getContent(), true);
        $user = new User();
        $user->exchangeArray($params);

        $user_id = $this->table->saveData($user);

        $user = $this->table->getData($user_id);

        return new JsonModel((array)$user);
    }

    public function show($id)
    {
        try {
            $user = $this->table->getData($id);
        } catch(Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        return new JsonModel((array)$user);
    }

    private function delete()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            throw new RuntimeException('Invalid Id');
        }

        $user = $this->table->getData($id);
        if ($user && $user->id) {
            $this->table->deleteData($id);
            // return new JsonModel((array)$user);
            return new JsonModel([
                'message' => 'Successfully removed.'
            ]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot delete user with identifier %d; does not exist',
                $id
            ));
        }
    }
}
