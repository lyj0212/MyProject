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
 * Group Class
 *
 * 회원 그룹관리 클래스 입니다.
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Group extends PL_Controller {

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
			'from' => 'member_group',
			'order' => array('sort'=>'ASC')
		);
		$data = $this->get_entries($model);

		$this->html['css'][] = '/_res/libs/sitemap/sitemap.css';
		$this->html['js'][] = '/_res/libs/sitemap/sortable.js';

		$view = array(
			'skin' => 'group/index',
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
	public function add()
	{
		$this->title = '그룹 추가';

		$id = $this->link->get_segment('id', FALSE);

		if( ! empty($id))
		{
			$model = array(
				'from' => 'member_group',
				'conditions' => array('where' => array('id' => $id)),
				'not_exists' => '_error_exists'
			);
			$data = $this->get_row($model);
		}
		else
		{
			$data = $this->set_default('member_group');
		}

		$view = array(
			'skin' => 'group/popup/add',
			'layout' => 'popup',
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
		$item_id = $this->input->post('item_id');
		foreach($item_id as $key => $id)
		{
			$model = array(
				'data' => array(
					'sort' => $key+1
				),
				'from' => 'member_group',
				'conditions' => array('where' => array('id' => $id))
			);
			$this->save_row($model);
		}

		message('저장 되었습니다.');
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
	public function save_group()
	{
		$this->form_validation->set_rules('title', '그룹명', 'required');
		if ($this->form_validation->run() === FALSE)
		{
			$this->add();
			return FALSE;
		}

		$id = $this->input->post('id');

		if(empty($id))
		{
			$model = array(
				'select' => 'MAX(sort) as new_sort',
				'from' => 'member_group'
			);
			$row = $this->get_row($model);

			$data['sort'] = $row['data']['new_sort']+1;
		}

		$data['title'] = $this->input->post('title');
		$data['admin'] = ($this->input->post('admin') == 'Y') ? 'Y' : 'N';

		$model = array(
			'from' => 'member_group',
			'conditions' => ( ! empty($id)) ? array('where' => array('id' => $id)) : NULL,
			'data' => $data
		);
		$affect_id = $this->save_row($model);

		message('저장 되었습니다.');
		script('parentreload');
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function delete_group()
	{
		$id = $this->link->get_segment('id');

		$model = array(
			'from' => 'member_group',
			'conditions' => array('where' => array('id' => $id))
		);
		$this->delete_row($model);

		message('삭제 되었습니다.');
		redirect($this->link->get(array('action'=>'index', 'id'=>NULL)));
	}

}
