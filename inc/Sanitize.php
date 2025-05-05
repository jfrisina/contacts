<?php
// Group together related classes, functions, or constants under a common label
namespace SARE\Contacts;

// Prevent Direct Access
( defined( 'ABSPATH' ) ) || die;

class Sanitize {
	/**
	 * Sanitize form field values
	 *
	 * @param string $value
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function text( string $value ): string { // says that the end result will be a string (typecasting)

		// Check encoding to make sure it is UTF-8
		if ( mb_check_encoding( $value, "UTF-8" ) === false ) {
			throw new \Exception( "This is not valid UTF-8" );
		}

		// Strip all tags
		$value = strip_tags( $value );

		// Remove white space
		$value = trim( $value );

		// Strip percent characters
		$value = str_replace( '%', '', $value );

		return $value;
	}

	/**
	 * Sanitize email
	 *
	 * @param string $email
	 *
	 * @return string
	 */
	public static function email( string $email ): string {

		// put all to lowercase
		$value = strtolower( $email );

		// Allowed character regular expression: /[^a-z0-9+_.@-]/i.
		$value = preg_replace( '/[^a-z0-9+_.@-]/i', '', $value );

		// trim
		$value = trim( $value );

		return $value;
	}
}