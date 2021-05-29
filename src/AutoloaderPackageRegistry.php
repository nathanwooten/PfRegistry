<?php

namespace nathanwooten;

use nathanwooten\RegistryType;

class RegistryAutoloaderPackage extends RegistryType
{

	public function set( $name, $value = null ) {

		if ( $this->checkType( $value ) ) {

			return parent::set( $name, $value );
		}

	}

}
