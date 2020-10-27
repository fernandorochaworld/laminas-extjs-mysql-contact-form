<?php

declare(strict_types=1);

namespace Form\Plugin;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Form\Model\UserTable;

class AuthPlugin extends AbstractPlugin
{
	protected $authenticationService;
	protected $userTable;

	public function __construct(
		// AuthenticationService $authenticationService, 
		UserTable $userTable
	)
	{
		$this->authenticationService = new AuthenticationService();
		$this->userTable = $userTable;
	}

	public function __invoke()
	{
		if(!$this->authenticationService instanceof AuthenticationServiceInterface)
		{
			return;
		}

		if(!$this->authenticationService->hasIdentity()){
			return;
		}

		return $this->userTable->getData(
			(int)$this->authenticationService->getIdentity()->id
		);
	}
}
