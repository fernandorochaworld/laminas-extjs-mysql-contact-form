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
use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Result;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Session\SessionManager;
use RuntimeException;

class LoginController extends AbstractActionController
{
    private $authenticationService;
    private $userTable;
    private $adapter;

    public function __construct($authenticationService, $adapter, $userTable)
    {
		$this->authenticationService = $authenticationService;
		$this->adapter = $adapter;
		$this->userTable = $userTable;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function testAction()
    {
		if ($this->authenticationService->hasIdentity()) {
            $storage = $this->authenticationService->getStorage();
            die(var_dump($storage->read()->email));
        }
    }

	public function logoutAction()
	{
		if ($this->authenticationService->hasIdentity()) {
			$this->authenticationService->clearIdentity();
		}

		return $this->redirect()->toRoute('login');
	}

    public function loginAction()
    {
		// if ($this->authenticationService->hasIdentity()) {
        //     die(var_dump($this->authenticationService->getIdentity()));
		// 	return $this->redirect()->toRoute('home');
        // }

        $request = $this->getRequest();
        $data = json_decode($request->getContent(), true);

        // $data = [
        //     'email' => 'admin@test',
        //     'password' => 'password',
        //     'recall' => 1,
        // ];
        
        $authAdapter = new CredentialTreatmentAdapter($this->adapter);
        $authAdapter->setTableName($this->userTable->getTable())
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('password')
                    ->getDbSelect()->where(['email' => $data['email']]);

        # data from loginForm
        $authAdapter->setIdentity($data['email']);

        # password hashing class
        $hash = new Bcrypt();

        # well let us use the email address from the form to retrieve data for this user
        $info = $this->userTable->fetchAccountByEmail($data['email']);

        // die(var_dump((new Bcrypt())->create($data['password'])));
        # now compare password from form input with that already in the table
        if ($hash->verify($data['password'], $info->getPassword())) {
            $authAdapter->setCredential($info->getPassword());
        } else {
            $authAdapter->setCredential(''); # why? to gracefully handle errors
        }

        $authResult = $this->authenticationService->authenticate($authAdapter);
        switch ($authResult->getCode()) {
            case Result::FAILURE_IDENTITY_NOT_FOUND:
                throw new RuntimeException('Unknow email address!');
                break;

            case Result::FAILURE_CREDENTIAL_INVALID:
                throw new RuntimeException('Incorrect Password!');
                break;
                
            case Result::SUCCESS:
                // if ($data['recall'] == 1) {
                    $ssm = new SessionManager();
                    $ttl = 1814400; # time for session to live
                    $ssm->rememberMe($ttl);
                // }

                $storage = $this->authenticationService->getStorage();
                $storage->write($authAdapter->getResultRowObject(null, ['created', 'modified']));
                // die(var_dump($storage->read()));
                break;		
            
            default:
                throw new RuntimeException('Authentication failed. Try again.');
                break;
        }

        return new JsonModel([
            'message' => 'Login Successfull.'
        ]);
    }
}
