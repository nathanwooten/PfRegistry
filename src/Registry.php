<?php

namespace nathanwooten;

use Exception;

class Registry {

	protected $name = 'AppWrap';

	protected static $container = [];
	public static $registry;

	protected static $type = [ 'AutoloaderPackage', 'Filesystem', 'Template' ];

	public static function set( $name, $value = null ) {

		static::before();

		$args = func_get_args();
		$type = ! isset( $args[2] ) || ! $args[2] ? static::hasTypeCheck( $value ) : $args[2];
		if ( ! $type ) {
			throw new Exception( 'Unknown type' );
		}

		if ( 'container' === $type ) {
			static::$container[ $name ] = $value;
			return;
		}

		static::registry( $type )->set( $name, $value );

	}

	public static function get( $type )
	{

		$got = [];
		foreach ( static::all() as $key => $registry ) {

			if ( $type === $key ) {
				$got[ $key ] = static::registry( $type );

				break;
			}

			$name = $type;

			if ( $registry->has( $name ) ) {
				$got[ $name ] = $registry->get( $name );

				break;
			}
		}

		if ( 1 === count( $got ) ) {
			$got = current( $got );
		}

		return $got;

	}

	public static function has( $type, $name = null )
	{

		static::before();

		if ( ! array_key_exists( $type, static::registry()->all() ) ) return false;

		if ( isset( $name ) ) {
			return static::$registry->get( $type )->has( $name );
		}

		return true;

	}

	public static function all()
	{

		static::before();

		static::addType();

		$all = static::registry()->all();

		return $all;

	}

	public static function hasTypeCheck( $value )
	{

		if ( is_string( $value ) && is_readable( $value ) ) {
			return 'Filesystem';
		}	

		foreach ( static::$type as $type ) {

			$qualified = 'nathanwooten\\' . $type;
			if ( is_object( $value ) && is_a( $value, $qualified ) ) {
				return $type;
			}
		}

		if ( static::hasContainerType( $value ) ) {
			$type = 'container';
			return $type;
		}

		return false;

	}

	public static function hasContainerType( $value )
	{

		if (
			! is_string( $value ) &&
			! is_integer( $value ) &&
				! is_array( $value ) &&
			( ! is_object( $value ) || ! is_a( $value, 'nathanwooten\nathanwootenInterface' ) ) ) {
			return false;
		}

		return true;

	}


	public static function add( $type )
	{

		static::$registry->set( $type, static::create( $type, $type ) );

		if ( ! in_array( $type, static::$type ) ) {

			array_push( static::$type, $type );
		}

	}

	public static function addType()
	{

		foreach ( static::$type as $type ) {
			if ( ! static::registry()->has( $type ) ) {

				static::add( $type );
			}
		}

	}

	public static function create( $registryName = null, string $type = null )
	{

		if ( null === $type ) {
			$class = __NAMESPACE__ . '\\' . 'RegistryAbstract';
		} else {

			$class = __NAMESPACE__ . '\\' . $type . 'Registry';
			if ( ! class_exists( $class ) ) {
				$class = __NAMESPACE__ . '\\' . 'RegistryAbstract';
			}
		}

		$registry = new $class;
		$registry->setName( $registryName );

		return $registry;

	}

	public static function registry( $type = null )
	{

		if ( ! isset( static::$registry ) ) {
			static::$registry = static::create();
			static::$registry->setName( 'App' );
		}
		if ( is_null( $type ) ) {
			return static::$registry;
		}

		if( ! static::$registry->has( $type ) ) {
			static::add( $type );
		}

		return static::$registry->get( $type );

	}

	protected static function before()
	{

		static::registry();
		static::addType();

	}

	protected function __construct()
	{

		$this->before();

	}

	public static function getInstance()
	{

		if ( ! isset( static::$instance ) ) {
			static::$instance = new static;
		}

		return static::$instance;

	}

}
