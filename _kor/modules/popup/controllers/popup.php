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
 * Popup Class
 *
 * 팝업관리 클래스 입니다.
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Popup extends PL_Controller {

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
		$model = array(
			'from' => 'popup',
			'limit' => 20,
			'paging' => TRUE,
			'paging_action' => 'index',
			'order' => array('id'=>'DESC'),
			'include_file' => TRUE,
		);
		$data = $this->get_entries($model);

		$view = array(
			'skin' => 'popup/popup/index',
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
	public function view()
	{
		$id = $this->link->get_segment('id');

		$model = array(
			'from' => 'popup',
			'conditions' => array('where' => array('id' => $id)),
			'not_exists' => '_error_exists'
		);
		$data = $this->get_row($model);

		$view = array(
			'skin' => 'popup/popup/view',
			'layout' => FALSE,
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
	public function write()
	{
		$id = $this->link->get_segment('id', FALSE);

		if( ! empty($id))
		{
			$model= array(
				'from' => 'popup',
				'conditions' => array('where' => array('id' => $id)),
				'include_file' => 'ALL',
				'not_exists' => '_error_exists'
			);
			$data = $this->get_row($model);
		}
		else
		{
			$data = $this->set_default('popup');
		}

		$view = array(
			'skin' => 'popup/popup/write',
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
	public function save()
	{
		$this->form_validation->set_rules('subject', '제목', 'trim|required');
		$this->form_validation->set_rules('start_date', '팝업 시작일', 'trim|required');
		$this->form_validation->set_rules('end_date', '팝업 종료일', 'trim|required');
		$this->form_validation->set_rules('width', '너비', 'trim|required');
		$this->form_validation->set_rules('height', '높이', 'trim|required');
		$this->form_validation->set_rules('top', '상단위치', 'trim|required');
		$this->form_validation->set_rules('left', '왼쪽위치', 'trim|required');
		if($this->form_validation->run() === FALSE)
		{
			$this->write();
			return FALSE;
		}

		$id = $this->input->post('id');
		$data['subject'] = $this->input->post('subject');
		$data['start_date'] = $this->input->post('start_date');
		$data['end_date'] = $this->input->post('end_date');
		$data['width'] = $this->input->post('width');
		$data['height'] = $this->input->post('height');
		$data['top'] = $this->input->post('top');
		$data['left'] = $this->input->post('left');
		$data['contents'] = $this->input->post('contents');
		$data['contents'] = preg_replace(sprintf("|src=[\"']?%s(.*?)[\"']|i", rtrim(site_url(), '/')), "src=\"$1\"", $data['contents']);
		$data['contents'] = preg_replace(sprintf("|href=[\"']?%s(.*?)[\"']|i", rtrim(site_url(), '/')), "href=\"$1\"", $data['contents']);

		$model = array(
			'from' => 'popup',
			'conditions' => ( ! empty($id)) ? array('where'=>array('id'=>$id)) : NULL,
			'data' => $data,
			'include_file' => TRUE
		);
		$affect_id = $this->save_row($model);

		message('저장 되었습니다.');
		redirect($this->link->get(array('action'=>'index', 'id'=>NULL)));
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function delete()
	{
		$id = $this->link->get_segment('id');

		$model = array(
			'from' => 'popup',
			'conditions' => array('where' => array('id' => $id)),
		);
		$this->delete_row($model);

		message('삭제 되었습니다.');
		redirect($this->link->get(array('action'=>'index', 'id'=>NULL)));
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	protected function _error_exists()
	{
		script('back', '팝업이 존재하지 않습니다.');
	}

}