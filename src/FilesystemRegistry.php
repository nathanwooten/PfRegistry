<?php

namespace nathanwooten;

use nathanwooten\RegistryAbstract;

class RegistryFilesystem extends RegistryAbstract
{

	public function set( $name, $value = null ) {
var_dump( $name );
		if ( is_string( $value ) ) {
			preg_match_all( '/\{\{.*?\}\}/', $value, $matches );

			foreach ( $matches[0] as $match ) {
				$matchName = trim( $match, '{}' );

				if ( static::has( $matchName ) ) {
					$got = static::get( $matchName );

					if ( is_string( $got ) ) {
						$value = str_replace( $match, $got, $value );
					}
				}
			}
		}

		parent::set( $name, $value );

	}

}
