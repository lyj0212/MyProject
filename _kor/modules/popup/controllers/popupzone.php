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
 * Popupzone Class
 *
 * 팝업존관리 클래스 입니다.
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Popupzone extends PL_Controller {

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
			'from' => 'popupzone',
			'limit' => 20,
			'paging' => TRUE,
			'paging_action' => 'index',
			'order' => array('id'=>'DESC'),
			'include_file' => TRUE,
		);
		$data = $this->get_entries($model);

		$view = array(
			'skin' => 'popupzone/index',
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
				'from' => 'popupzone',
				'conditions' => array('where' => array('id' => $id)),
				'include_file' => 'ALL',
				'not_exists' => '_error_exists'
			);
			$data = $this->get_row($model);

			if( ! empty($data['files'][$id]))
			{
				foreach($data['files'][$id] as $item)
				{
					$data[$item['target']][] = $item;
				}
			}
		}
		else
		{
			$data = $this->set_default('popupzone');
		}

		$view = array(
			'skin' => 'popupzone/write',
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
		//$this->form_validation->set_rules('link', '링크', 'trim|required');
		$this->form_validation->set_rules('sort', '순서', 'trim|required|numeric');
		if($this->form_validation->run() === FALSE)
		{
			$this->write();
			return FALSE;
		}

		$id = $this->input->post('id');
		$data['subject'] = $this->input->post('subject');
		$data['link'] = $this->input->post('link');
		$data['target'] = $this->input->post('target');
		$data['sort'] = $this->input->post('sort');

		$model = array(
			'from' => 'popupzone',
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
			'from' => 'popupzone',
			'conditions' => array('where' => array('id' => $id))
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
		script('back', '비주얼이 존재하지 않습니다.');
	}

}