<?php

declare(strict_types=1);

namespace Form\View\Helper;

use Form\Plugin\AuthPlugin;
use Interop\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\View\Helper\AbstractHelper;
use User\Model\UserTable;

class AuthHelper extends AbstractHelper
{
	protected $authPlugin;

	public function getAuthPlugin()
	{
		return $this->authPlugin;
	}

	public function setAuthPlugin($authPlugin)
	{
		if(!$this->authPlugin instanceof AuthPlugin)
		{
			throw new \InvalidArgumentException(
				sprintf('
					%s expects a %s instance; received %s', 
					__METHOD__, AuthPlugin::class,
					(is_object($authPlugin) ? get_class($authPlugin) : gettype($authPlugin))
			    )
			);
		}

		$this->authPlugin = $authPlugin;
	}

	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		if(null === $this->authPlugin) {
			$this->setAuthPlugin(
				new AuthPlugin(
					// $container->get(AuthenticationService::class),
					$container->get(UserTable::class)
				)
			);
		}

		return $this->getAuthPlugin();
	}
}
