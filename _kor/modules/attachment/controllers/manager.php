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
 * File Class
 *
 * 게시판 클래스 입니다.
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Manager extends PL_Controller {

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
			'select' => 'A.*, B.name as company_name',
			'from' => 'files A',
			'join' => array('config_company B', 'A.company_id=B.id', 'left'),
			'limit' => 50,
			'paging' => TRUE,
			'order' => array('A.created' => 'DESC'),
			'include_search' => TRUE,
			'include_company' => FALSE
		);
		$data = $this->get_entries($model);

		$model = array(
			'select' => 'SUM(file_size) as total_size',
			'from' => 'files',
			'include_company' => FALSE
		);
		$total_size = $this->get_row($model);

		$data['total_size'] = $total_size['data']['total_size'];

		$view = array(
			'skin' => 'manager/index',
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
	public function delete_temporary()
	{
		$model = array(
			'from' => 'files',
			'conditions' => array('where' => array('files.use' => 'N')),
			'include_company' => FALSE
		);
		$data = $this->get_entries($model);

		foreach($data['data'] as $item)
		{
			@unlink(sprintf('%s%s%s', $item['upload_path'], $item['raw_name'], $item['file_ext']));
		}

		$this->delete_row($model);

		message('삭제 되었습니다.');
		redirect($this->link->get(array('action'=>'index')));
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function delete_checked()
	{
		$aid = urldecode($this->link->get_segment('aid', FALSE));

		if(empty($aid))
		{
			script('back', '삭제할 파일을 선택해 주세요.');
		}

		$model = array(
			'from' => 'files',
			'include_company' => FALSE
		);

		$checked_id = explode('|', $aid);
		$model['conditions']['where_in']['id'] = $checked_id;

		$data = $this->get_entries($model);

		foreach($data['data'] as $item)
		{
			@unlink(sprintf('%s%s%s', $item['upload_path'], $item['raw_name'], $item['file_ext']));
		}

		$this->delete_row($model);

		message('삭제 되었습니다.');
		redirect($this->link->get(array('action'=>'index', 'aid'=>NULL)));
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
			'from' => 'files',
			'conditions' => array('where' => array('id' => $id)),
			'not_exists' => '_error_exists',
			'include_company' => FALSE
		);
		$row = $this->get_row($model);

		@unlink(sprintf('%s%s%s', $row['data']['upload_path'], $row['data']['raw_name'], $row['data']['file_ext']));

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
		script('back', '게시물이 존재하지 않습니다.');
	}

}
