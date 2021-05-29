<?php

namespace nathanwooten;

use nathanwooten\RegistryAbstract;

class RegistryType extends RegistryAbstract
{

	public function checkType( $value )
	{

		if ( is_a( $value, $this->getTYpe() ) ) {
			return true;
		}

		return false;

	}

	public function getType()
	{

		return str_replace( __NAMESPACE__ . '\\', 'Registry', '', static::class );

	}

}
