<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Plani Base Module
 *
 * PHP 5.1.6 or newer AND MYSQL 5.0 or newer
 *
 * @package	Plani Module
 * @author	Shin Donguk
 * @copyright	Copyright (c) 2012, Plani, Inc.
 * @link	http://plani.co.kr
 * @since	 Version 2.0
 * @filesource
 */

require_once(dirname(dirname(dirname(__FILE__))).'/controller'.EXT);

// ------------------------------------------------------------------------

/**
 * Zipcode Class
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Search extends PL_Controller {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function index()
	{
		$this->title = ' 우편번호 검색';

		$data['target_zip'] = decode_url($this->link->get_segment('zip'));
		$data['target_address1'] = decode_url($this->link->get_segment('address1'));
		$data['target_address2'] = decode_url($this->link->get_segment('address2'));
		$data['request'] = decode_url($this->link->get_segment('request'));

		$view = array(
			'skin' => 'index',
			'layout' => 'popup',
			'data' => $data
		);
		$this->show($view);
	}

}
