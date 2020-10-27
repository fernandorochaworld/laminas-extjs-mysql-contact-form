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
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use RuntimeException;

class IndexController extends AbstractActionController
{
    protected $table;
    protected $priorityTable;
    protected $userTable;
    protected $adapter;
    protected $authenticationService;
    private const CREDENTIAL_MESSAGE = 'No credencials.';

    public function __construct($table, $priorityTable, $userTable, $adapter, $authenticationService)
    {
        $this->table = $table;
        $this->priorityTable = $priorityTable;
		$this->adapter = $adapter;
		$this->userTable = $userTable;
		$this->authenticationService = $authenticationService;
    }

    private function redirectToLogin() {
        return $this->redirect()->toRoute('login');
    }

    public function testAction()
    {
		if (!$this->authenticationService->hasIdentity()) {
			return $this->redirectToLogin();
        }
		if ($this->authenticationService->hasIdentity()) {
            $storage = $this->authenticationService->getStorage();
            die(var_dump($storage->read()->email));
        }
    }

    // public function loginAction()
    // {
	// 	$auth = new AuthenticationService();
	// 	// if($auth->hasIdentity()) {
    //     //     die(var_dump($auth->getIdentity()));
	// 	// 	return $this->redirect()->toRoute('login');
    //     // }
    //     $data = [
    //         'email' => 'admin@test',
    //         'password' => 'password',
    //         'recall' => 1,
    //     ];
        
    //     $authAdapter = new CredentialTreatmentAdapter($this->adapter);
    //     $authAdapter->setTableName($this->userTable->getTable())
    //                 ->setIdentityColumn('email')
    //                 ->setCredentialColumn('password')
    //                 ->getDbSelect()->where(['email' => $data['email']]);

    //     # data from loginForm
    //     $authAdapter->setIdentity($data['email']);

    //     # password hashing class
    //     $hash = new Bcrypt();

    //     # well let us use the email address from the form to retrieve data for this user
    //     $info = $this->userTable->fetchAccountByEmail($data['email']);

    //     // die(var_dump((new Bcrypt())->create($data['password'])));
    //     # now compare password from form input with that already in the table
    //     if ($hash->verify($data['password'], $info->getPassword())) {
    //         $authAdapter->setCredential($info->getPassword());
    //     } else {
    //         $authAdapter->setCredential(''); # why? to gracefully handle errors
    //     }

    //     $authResult = $auth->authenticate($authAdapter);
    //     switch ($authResult->getCode()) {
    //         case Result::FAILURE_IDENTITY_NOT_FOUND:
    //             throw new RuntimeException('Unknow email address!');
    //             break;

    //         case Result::FAILURE_CREDENTIAL_INVALID:
    //             throw new RuntimeException('Incorrect Password!');
    //             break;
                
    //         case Result::SUCCESS:
    //             if($data['recall'] == 1) {
    //                 $ssm = new SessionManager();
    //                 $ttl = 1814400; # time for session to live
    //                 $ssm->rememberMe($ttl);
    //             }

    //             $storage = $auth->getStorage();
    //             $storage->write($authAdapter->getResultRowObject(null, ['created', 'modified']));
    //             // die(var_dump($storage->read()));
    //             break;		
            
    //         default:
    //             $this->flashMessenger()->addErrorMessage('Authentication failed. Try again');
    //             return $this->redirect()->refresh(); # refresh the page to show error
    //             break;
    //     }

	// 	// $auth = new AuthenticationService();
	// 	// if($auth->hasIdentity()) {
	// 	// 	return $this->redirect()->toRoute('home');
	// 	// }
    //     // return $this->redirect()->toRoute('form/8');
    //     // return $this->redirect()->toRoute("form", ['id' => 7]);
    //     die('abcde');
    // }

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
        /* @var $forms AbstractResultSet */
        $forms = $this->table->fetchAll();
        return new JsonModel($forms);
    }

    private function save()
    {
        $request = $this->getRequest();
        $params = json_decode($request->getContent(), true);
        $form = new Form();
        $form->exchangeArray($params);

        $form_id = $this->table->saveData($form);

        $form = $this->table->getData($form_id);

        return new JsonModel((array)$form);
    }

    public function show($id)
    {
        try {
            $form = $this->table->getData($id);
        } catch(Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

        return new JsonModel((array)$form);
    }

    public function payloadAction()
    {
		if (!$this->authenticationService->hasIdentity()) {
            throw new RuntimeException($this->CREDENTIAL_MESSAGE);
        }
        $forms = $this->table->fetchAll();
        $priorities = $this->priorityTable->fetchAll();

        return new JsonModel([
            'forms' => $forms->toArray(),
            'priorities' => $priorities->toArray()
        ]);
    }

    private function delete()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            throw new RuntimeException('Invalid Id');
        }

        $form = $this->table->getData($id);
        if ($form && $form->id) {
            $this->table->deleteData($id);
            // return new JsonModel((array)$form);
            return new JsonModel([
                'message' => 'Successfully removed.'
            ]);
        } else {
            throw new RuntimeException(sprintf(
                'Cannot delete form with identifier %d; does not exist',
                $id
            ));
        }
    }
}
