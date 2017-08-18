<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Input Class
 *
 * Pre-processes global input data for security
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Input
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/input.html
 */
class MY_Input extends CI_Input {

 function __construct()
	{
		 parent::__construct();
	}

	function get($index = NULL, $xss_clean = TRUE)
	{
		return parent::get($index, $xss_clean);
	}

	function post($index = NULL, $xss_clean = TRUE)
	{
		return parent::post($index, $xss_clean);
	}

	function get_post($index = '', $xss_clean = TRUE)
	{
		return parent::get_post($index, $xss_clean);
	}

	function cookie($index = '', $xss_clean = TRUE)
	{
		return parent::cookie($index, $xss_clean);
	}

	function server($index = '', $xss_clean = TRUE)
	{
		return parent::server($index, $xss_clean);
	}

	public function request_headers($xss_clean = TRUE)
	{
		return parent::request_headers($xss_clean);
	}

	public function get_request_header($index, $xss_clean = TRUE)
	{
		return parent::get_request_header($index, $xss_clean);
	}

	// --------------------------------------------------------------------

	/**
	 * Clean Keys
	 *
	 * This is a helper function. To prevent malicious users
	 * from trying to exploit keys we make sure that keys are
	 * only named with alpha-numeric text and a few other items.
	 *
	 * @access	private
	 * @param	string
	 * @return	string
	 */
	function _clean_input_keys($str)
	{
		if ( ! preg_match("/^[a-z0-9:._\/-]+$/i", $str))
		{
			exit('Disallowed Key Characters.');
		}

		// Clean UTF-8 if supported
		if (UTF8_ENABLED === TRUE)
		{
			$str = $this->uni->clean_string($str);
		}

		return $str;
	}

}

/* End of file MY_Input.php */
