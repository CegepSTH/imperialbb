<?php

require_once($root_path."includes/functions.php");

/**
 * A class to create, fetch and validate CSRF tokens.
 * 
 * @author Michael Tran
 */
class CSRF {
	const TOKEN_SESSION_KEY = "CsrfToken";
	const HIDDEN_FIELD_NAME = "RequestValidationToken";

	/**
	 * generateRandomBytes Generates a buffer of pseudo-random bytes with a
	 * specified length.
	 * 
	 * @param $count The number of bytes to generate.
	 * @return A buffer of pseudo-random bytes.
	 */
	public static function generateRandomBytes($count) {
		if (function_exists("openssl_random_pseudo_bytes")) {
			// $strong_generation is taken by reference and indicates if OpenSSL
			// used a cryptographically strong algorithm to generate the bytes.
			return openssl_random_pseudo_bytes($count, $strong_generation);
		}

		if (is_readable("/dev/urandom")) {
			return file_get_contents("/dev/urandom", false, NULL, -1, $count);
		}

		// No random sources, use mt_rand.
		$buffer = "";
		for ($i = 0; $i < $count; $i++) {
			$buffer .= chr(mt_rand(0, 255));
		}

		return $buffer;
	}

	/**
	 * generateToken Generates the CSRF token.
	 * 
	 * It is the sha256 hash of a random buffer of 32 bytes.
	 *
	 * @return A string representing the CSRF token.
	 */
	 public static function generateToken()	{
		$token_bytes = self::generateRandomBytes(32);

		// Calling hash() with false returns the hash with hex digits.
		return hash("sha256", $token_bytes, false);
	}

	/**
	 * getToken Gets or creates the CSRF token.
	 * 
	 * @return A string with the CSRF token.
	 */
	public static function getToken() {
		if (!isset($_SESSION[self::TOKEN_SESSION_KEY])) {
			$_SESSION[self::TOKEN_SESSION_KEY] = self::generateToken();
		}

		return $_SESSION[self::TOKEN_SESSION_KEY];
	}

	/**
	 * validateToken Validates the CSRF token.
	 * 
	 * @param $tokenToValidate The token to validate. If it is not supplied,
	 * this function will fetch the token from the POST variables.
	 * @return A boolean indicating if the supplied token is valid.
	 */
	public static function validateToken($tokenToValidate = NULL) {
		if (is_null($tokenToValidate)) {
			if (isset($_POST[self::HIDDEN_FIELD_NAME])) {
				$tokenToValidate = $_POST[self::HIDDEN_FIELD_NAME];
			}
		}

		if (!is_string($tokenToValidate)) {
			return false;
		}

		if (!isset($_SESSION[self::TOKEN_SESSION_KEY])) {
			return false;
		}

		return $tokenToValidate === $_SESSION[self::TOKEN_SESSION_KEY];
	}

	/**
	 * validateTokenWithMessage Validates the CSRF token and shows an error
	 * message if it is not valid. This function never returns if the token
	 * is invalid.
	 * 
	 * @param $tokenToValidate The token to validate. If it is not supplied,
	 * this function will fetch the token from the POST variables.
	 */
	public static function validateTokenWithMessage($tokenToValidate = NULL) {
		$ret = self::validateToken($tokenToValidate);

		if ($ret) {
			return;
		}
		
		showMessage(ERR_CODE_INVALID_REQUEST);
	}

	/**
	 * validate Shortcut for validateTokenWithMessage with no parameters.
	 * 
	 */
	public static function validate() {
		self::validateTokenWithMessage();
	}

	/**
	 * getFormInput Gets the HTML as a hidden form input for the token.
	 * 
	 * @return The HTML with the token.
	 */
	public static function getHTML() {
		$token = self::getToken();

		return "<input type=\"hidden\" name=\"" . self::HIDDEN_FIELD_NAME . "\" value=\"" . $token . "\" />";
	}
}

?>

