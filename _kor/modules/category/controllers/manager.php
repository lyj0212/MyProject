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
 * Category Class
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Manager extends PL_Controller {

	public $category = array();

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
		$this->title = decode_url($this->link->get_segment('title'));
		$type = $this->link->get_segment('type');

		$model = array(
			'from' => 'global_category',
			'conditions' => array('where' => array('type' => $type)),
			'order' => array('sort' => 'ASC'),
			'callback' => '_process_category'
		);
		$this->get_entries($model);

		$data['data']['type'] = $type;

		$this->html['css'][] = '/_res/libs/sitemap/sitemap.css';
		$this->html['js'][] = '/_res/libs/sitemap/sitemap.js';

		$view = array(
			'skin' => 'index',
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
		$this->form_validation->set_rules('type', '카테고리 구분', 'trim|required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->index();
			return FALSE;
		}

		$type = $this->input->post('type');
		$item_id = $this->input->post('item_id');
		$parent_id = $this->input->post('parent_id');

		foreach($item_id as $key => $id)
		{
			$model = array(
				'data' => array(
					'parent_id' => $parent_id[$key],
					'sort' => $key+1
				),
				'from' => 'global_category',
				'conditions' => array('where' => array('id' => $id))
			);
			$this->save_row($model);
		}

		// 캐쉬 삭제
		$this->cache->delete(sprintf('_category_%s', $type));

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
	public function category_add()
	{
		$this->title = decode_url($this->link->get_segment('title'));

		$id = $this->link->get_segment('id', FALSE);

		if( ! empty($id))
		{
			$model = array(
				'from' => 'global_category',
				'conditions' => array('where' => array('id' => $id)),
				'not_exists' => '_error_exists_category'
			);
			$data = $this->get_row($model);
		}
		else
		{
			$data = $this->set_default('global_category');
			$data['data']['type'] = $this->link->get_segment('type');
			$data['data']['parent_id'] = $this->link->get_segment('parent_id', FALSE);
		}

		$view = array(
			'skin' => 'popup/category_add',
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
	public function category_save_item()
	{
		$this->form_validation->set_rules('type', '카테고리 구분', 'trim|required');
		$this->form_validation->set_rules('title', '항목', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->category_add();
			return FALSE;
		}

		$id = $this->input->post('id');

		if(empty($id))
		{
			$data['type'] = $this->input->post('type');
			$data['parent_id'] = $this->input->post('parent_id');

			$model = array(
				'select' => 'MAX(sort) as new_sort',
				'from' => 'global_category',
				'conditions' => array('where' => array('type' => $data['type']))
			);
			$row = $this->get_row($model);

			$data['sort'] = $row['data']['new_sort']+1;
		}

		$data['title'] = $this->input->post('title');

		$model = array(
			'data' => $data,
			'from' => 'global_category',
			'conditions' => ( ! empty($id)) ? array('where' => array('id' => $id)) : NULL
		);
		$affect_id = $this->save_row($model);

		// 캐쉬 삭제
		$this->cache->delete(sprintf('_category_%s', $data['type']));

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
	public function category_delete()
	{
		$type = $this->link->get_segment('type');
		$id = $this->link->get_segment('id');

		$model = array(
			'from' => 'global_category',
			'order' => array('sort' => 'ASC'),
			'callback' => '_process_category'
		);
		$this->get_entries($model);

		if(isset($this->category[$type][$id]))
		{
			message('하위 항목이 존재하여 삭제 할 수 없습니다.', 'error');
			redirect($this->link->get(array('action'=>'index')));
		}

		$model = array(
			'from' => 'global_category',
			'conditions' => array('where' => array('id' => $id))
		);
		$this->delete_row($model);

		// 캐쉬 삭제
		$this->cache->delete(sprintf('_category_%s', $type));

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
	public function _construct_category($type, $parent=0)
	{
		if(isset($this->category[$type][$parent]))
		{
			$data['data'] = $this->category[$type][$parent];
		}
		else
		{
			return FALSE;
		}

		$view = array(
			'skin' => '_category_item',
			'layout' => 'popup',
			'data' => $data,
			'return' => TRUE
		);
		echo $this->show($view);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	protected function _process_category(&$item)
	{
		$this->category[$item['type']][$item['parent_id']][] = $item;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	protected function _error_exists_category()
	{
		script('back', '항목이 존재하지 않습니다.');
	}

}
