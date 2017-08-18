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
 * Editor Class
 *
 * 다음 에디터 클래스 입니다.
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Editor extends PL_Controller {

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
		$this->output->enable_profiler(FALSE);

		$data['editor_path'] = sprintf('/%s/modules/daumeditor/', SITE);

		$this->variable->html['css'][] = './modules/daumeditor/css/editor.css';

		$view = array(
			'skin' => 'editor',
			'data' => $data
		);
		$this->show($view);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function textCrawler()
	{
		$this->output->enable_profiler(FALSE);

		$this->load->library('LinkPreview');

		$text = $this->input->post('text');
		$text = ' '.str_replace("\n", ' ', $text);
		$imageQuantity = 5;
		$header = '';

		$answer = $this->linkpreview->crawl($text, $imageQuantity, $header);
		echo $answer;
	}

}
