<?php

namespace nathanwooten;

use nathanwooten\RegistryWrapper;
use nathanwooten\RegistryRegister;

trait RegistryTrait
{

	public function getRegistry()
	{

		return new RegistryWrapper;

	}

	public function register()
	{

		$registry = $this->getRegistry();

		$name = $this->getClassname();
		if ( ! $registry->has( $name ) ) {

			$registry->set( $name, $this );
		}

	}

	public function getClassname()
	{

		return str_replace( __NAMESPACE__ . '\\', '', static::class );

	}

}
