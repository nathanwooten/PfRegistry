<?php

namespace nathanwooten;

use Exception;

class Registry {

	protected static $instance;

	protected static $registry = [
		'fs' => []
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
		$value = $fs[ $name ];
		return $value;

	}

	public static function getInstance()
	{

		if ( ! isset( static::$instance ) ) {

			static::$instance = new static;
		}

		return static::$instance;

	}

}
