<?php

declare(strict_types=1);

namespace Form\Plugin\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Form\Model\UserTable;
use Form\Plugin\AuthPlugin;
use Laminas\Authentication\AuthenticationService;

class AuthPluginFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new AuthPlugin(
			// $container->get(AuthenticationService::class),
			$container->get(UserTable::class)
		);
	}
}
