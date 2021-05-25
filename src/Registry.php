<?php

namespace nathanwooten;

use Exception;

class Registry {

	protected static $instance;

	protected static $registry = [
		'al' => [],
		'fs' => [],
		'pg' => []
	];

	protected static $protected = [ 'fs' ];

	public static function set( $name, $data = null ) {

		if ( in_array( $name, static::$protected ) ) {
			throw new Exception;
		}
		if ( is_string( $data ) ) {
			preg_match_all( '/\{\{.*?\}\}/', $data, $matches );

			foreach ( $matches[0] as $match ) {
				$matchName = trim( $match, '{}' );

				if ( static::has( $matchName ) ) {
					$got = static::get( $matchName );

					if ( is_string( $got ) ) {
						$data = str_replace( $match, $got, $data );
					}
				}
			}
		}

		if ( is_readable( $data ) ) {
			return static::setFS( $name, $data );
		}

		if ( ! isset( static::$registry[ $name ] ) ) {
			static::$registry[ $name ] = $data;
		}

	}

	public static function get( $name )
	{

		$has = static::has( $name );
		if ( ! $has ) {
			throw new Exception( $name . ' does not exist' );
		}

		switch ( $has ) {

			case 'base':
				$value = static::$registry[ $name ];
				break;

			default:
				$type = $has;
				$value = static::$registry[ $type ][ $name ];
				break;
		}

		return $value;

	}

	public static function all()
	{

		return static::$registry;

	}

	public static function has( $name )
	{

		if ( array_key_exists( $name, static::$registry ) ) {
			return 'base';
		}

		foreach ( static::$protected as $type ) {

			if ( array_key_exists( $name, static::$registry[ $type ] ) ) {
				return $type;
			}
		}

		return false;

	}

	public static function setFS( $name, $readable )
	{

		if ( ! isset( static::$registry[ 'fs' ][ $name ] ) ) {

			static::$registry[ 'fs' ][ $name ] = $readable;
		}

	}

	public static function getFS( $name )
	{

		$fs = static::get( 'fs' );

		if ( array_key_exists( $name, $fs ) ) {
			return $fs[ $name ];
		}

		throw new Exception;

	}

	public static function setAL( $namespace, $dir )
	{

		if ( ! is_readable( $dir ) ) {
			throw new Exception( 'Unreadable directory' );
		}

		static::$registry[ 'al' ][ $namespace ] = $dir;

	}

	public static function getAL( $namespaceOrDir ) {

		if ( is_readable( $namespaceOrDir ) ) {
			$type = 'dir';
			$dir = $namespaceOrDir;
		} else {
			$type = 'namespace';
			$namespace = $namespaceOrDir;
		}

		switch ( $type ) {

			case 'dir':

				foreach ( static::$registry[ 'al' ] as $ns => $directory ) {

					if ( $dir === $directory ) return $ns;
				}
				break;

			case 'namespace':

				if ( array_key_exists( $namespace, static::$registry[ 'al' ] ) ) {

					return static::$registry[ 'al' ][ $namespace ];
				}
		}

		throw new Exception( 'Autoloader value does not exist' );

	}

	public static function setPG( Page $page ) {

		static::$registry[ 'pg' ][ $page->getName() ] = $page;

	}

	public static function getPG( $name )
	{

		return array_key_exists( $name, static::$registry[ 'pg' ] ) ? static::$registry[ 'pg' ][ $name ] : null;

	}

	public static function getInstance()
	{

		if ( ! isset( static::$instance ) ) {

			static::$instance = new static;
		}

		return static::$instance;

	}

}
