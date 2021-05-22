<?php

namespace nathanwooten;

class RegistryWrapper {

	public function get( $name )
	{

		return Registry::get( $name );

	}

	public function set( $name, $value = null )
	{

		preg_match_all( '/(*.?)/', $value, $matches );

var_dump( $matches );


		return Registry::set( $name, $value );

	}

	public function __call( $method, array $args = null )
	{

		if ( is_callable( 'nathanwooten\Registry', $method ) ) {

			return Registry::$method( ...(array) $args );
		}

	}

	public function __invoke( $method, array $args = null ) {

		return $this->$method( ...(array) $args );

	}

}
