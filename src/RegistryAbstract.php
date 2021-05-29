<?php

namespace nathanwooten;

class RegistryAbstract {

	public $container = [];
	protected $name = null;

	public function set( $name, $value ) {

		$name = (string) $name;

		$this->container[ $name ] = $value;

	}

	public function get( $name ) {

		return $this->has( $name ) ? $this->container[ $name ] : null;

	}

	public function has( $name ) {

		return array_key_exists( $name, $this->container );

	}

	public function all()
	{

		return $this->container;

	}

	public function setName( $name )
	{

		$this->name = $name;

	}

	public function getName()
	{

		return $this->name;

	}


}
