<?php
/**
 * DRAIWIKI
 * Open source wiki software
 *
 * @version     1.0 Alpha 1
 * @author      Robert Monden
 * @copyright   DraiWiki, 2017
 * @license     Apache 2.0
 */

namespace DraiWiki\admin\models;

if (!defined('DWA')) {
	header('Location: ../index.php');
	die('You\'re really not supposed to be here.');
}

use DraiWiki\admin\controllers\ModelController;
use DraiWiki\src\core\controllers\Registry;

/**
 * This class generates the error messages themselves.
 *
 * @since		1.0 Alpha 1
 */
class Error extends ModelController {

	/**
	 * @var string $_title The error message header. This is usually something like 'OMG, something went wrong!'
	 */
	private $_title;

	/**
	 * @var string $_message Detailed information about the error message (but only if we're logged in)
	 */
	private $_message;

	/**
	 * @var boolean $_languageFallback Whether or not the language files have been loaded yet.
	 */
	private $_languageFallback;

	/**
	 * @var boolean $_isLoggedIn Whether or not we can show sensitive data. Logged in = admin.
	 */
	private $_isLoggedIn;

	/**
	 * @var Locale $_locale An object of the locale class. It contains locale-related data (yeah, really!)
	 */
	private $_locale;

	/**
	 * Create a new instance of the Error class.
	 * @param string $message The error message
	 * @param boolean $languageFallback Should be set to true if no language files have been loaded yet
	 */
	public function __construct($message, $parameters = [], $languageFallback = false) {
		$this->_locale = Registry::get('locale');

		$this->_title = $languageFallback ? 'Oops! Something went wrong.' : $this->locale->read('error', 'something_went_wrong');
		$this->_languageFallback = $languageFallback;

		/**
		 * @todo This properly should have its value assigned dynamically based on whether or not we're dealing with an admin.
		 */
		$this->_isLoggedIn = false;

		// Users who aren't logged in shouldn't be able to see detailed error messages
		if ($this->_isLoggedIn)
			$this->_message = $message;
		else if ($languageFallback)
			$this->_message = 'An error occurred. Please try reloading the page.';
		else
			$this->_message = $this->_locale->read('error', 'UNKNOWN_ERROR_OCCURRED');

		if (!empty($parameters)) {
			foreach ($parameters as $key => $value) {
				$this->_message = str_replace('{' . $key . '}', $value, $this->_message);
			}
		}
	}

	/**
	 * @return string The value of $_title
	 */
	public function getTitle() {
		return $this->_title;
	}

	/**
	 * @return string The value of $_message
	 */
	public function getMessage() {
		return $this->_message;
	}

	/**
	 * @return string The value of $_detailedMessage
	 */
	public function getDetailedMessage() {
		return $this->_detailedMessage;
	}
}
