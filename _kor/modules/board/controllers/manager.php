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
 * Bbs Class
 *
 * 게시판 클래스 입니다.
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Manager extends PL_Controller {

	public $tableid;
	public $category = array();
	public $list_object = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_initialize();
	}

	private function _initialize()
	{
		$this->tableid = $this->link->get_segment('tableid', FALSE);

		$this->load->config('default');
		$this->list_object = config_item('list_section');
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
			'from' => 'bbs_setup',
			'limit' => 20,
			'paging' => TRUE
		);
		$data = $this->get_entries($model);

		$view = array(
			'skin' => 'manager/list',
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
		if( ! empty($this->tableid))
		{
			$model = array(
				'from' => 'bbs_setup',
				'conditions' => array('where' => array('tableid' => $this->tableid)),
				'not_exists' => '_error_exists'
			);
			$data = $this->get_row($model);
			if( ! empty($data['data']['list']))
			{
				$data['data']['list'] = unserialize($data['data']['list']);
			}
		}
		else
		{
			$data = $this->set_default('bbs_setup');
			$data['data']['limit'] = 20;
		}

		$data['active'] = $this->link->get_segment('active', FALSE);

		$view = array(
			'skin' => 'manager/_tab',
			'data' => $data,
			'return' => TRUE
		);
		$tab = $this->show($view);

		$view = array(
			'data' => $data,
			'return' => TRUE
		);
		switch($data['active'])
		{
			// 게시판 목록 설정
			case 'list' :
				$this->html['js'][] = './modules/board/js/select_order.js';

				$view['skin'] = 'manager/setup_list';
				break;

			// 게시판 카테고리 설정
			case 'category' :
				$model = array(
					'from' => 'bbs_category',
					'conditions' => array(),
					'order' => array('sort' => 'ASC'),
					'callback' => '_process_category'
				);
				$this->get_entries($model);

				$this->html['css'][] = '/_res/libs/sitemap/sitemap.css';
				$this->html['js'][] = '/_res/libs/sitemap/sitemap.js';

				$view['skin'] = 'manager/setup_category';
				break;

			// 게시판 디자인 설정
			case 'design' :
				$this->html['css'][] = '/_res/libs/colorpicker/css/colorpicker.css';
				$this->html['js'][] = '/_res/libs/colorpicker/js/colorpicker.js';

				$view['skin'] = 'manager/setup_design';
				break;

			// 게시판 기본 설정
			default :
				$view['skin'] = 'manager/setup_default';
		}
		$body = $this->show($view);

		$this->output->set_output($tab.$body);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function save_default()
	{
		$mode = $this->input->post('mode');

		$this->form_validation->set_rules('tableid', '게시판 아이디', 'trim|required|alpha_numeric');
		$this->form_validation->set_rules('title', '게시판 이름', 'trim|required');
		$this->form_validation->set_rules('type', '게시판 타입', 'trim|required');
		$this->form_validation->set_rules('limit', '리스트 글 수', 'trim|required|is_natural_no_zero');

		if ($this->form_validation->run() === FALSE)
		{
			$this->write();
			return FALSE;
		}

		$data['tableid'] = $this->input->post('tableid');
		$data['title'] = $this->input->post('title');
		$data['type'] = $this->input->post('type');
		$data['limit'] = $this->input->post('limit');
		$data['use_category'] = ($this->input->post('use_category') == 'Y') ? 'Y' : 'N';

		if($mode != 'modify')
		{
			$model = array(
				'from' => 'bbs_setup',
				'conditions' => array('where' => array('tableid' => $data['tableid']))
			);
			if($this->check_exists($model))
			{
				message('동일한 게시판 아이디가 존재합니다.', 'error');
				$this->write();
				return FALSE;
			}

			// 게시판 목록 기본 설정
			$data['list'] = serialize(array('no', 'subject', 'name', 'created'));
		}

		$model = array(
			'data' => $data,
			'from' => 'bbs_setup',
			'conditions' => ($mode == 'modify') ? array('where'=>array('tableid'=>$data['tableid'])) : NULL,
			'update_cache' => sprintf('board_%s', $this->tableid)
		);
		$this->save_row($model);

		message('저장 되었습니다.');
		redirect($this->link->get(array('action'=>'write', 'tableid'=>$data['tableid'])));
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function save_list()
	{
		$this->form_validation->set_rules('tableid', '게시판 아이디', 'trim|required|alpha_numeric');
		$this->form_validation->set_rules('list', '게시판 목록', 'trim|required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->write();
			return FALSE;
		}

		$tableid = $this->input->post('tableid');
		$data['list'] = serialize(explode(',', $this->input->post('list')));

		$model = array(
			'data' => $data,
			'from' => 'bbs_setup',
			'conditions' => array('where'=>array('tableid'=>$tableid)),
			'update_cache' => sprintf('board_%s', $this->tableid)
		);
		$this->save_row($model);

		message('저장 되었습니다.');
		redirect($this->link->get(array('action'=>'write')));
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function save_category()
	{
		$this->form_validation->set_rules('tableid', '게시판 아이디', 'trim|required|alpha_numeric');

		if ($this->form_validation->run() === FALSE)
		{
			$this->write();
			return FALSE;
		}

		$tableid = $this->input->post('tableid');
		$item_id = $this->input->post('item_id');
		$parent_id = $this->input->post('parent_id');

		foreach($item_id as $key => $id)
		{
			$model = array(
				'data' => array(
					'parent_id' => $parent_id[$key],
					'sort' => $key+1
				),
				'from' => 'bbs_category',
				'conditions' => array('where' => array('id' => $id)),
				'update_cache' => sprintf('board_category_%s', $this->tableid)
			);
			$this->save_row($model);
		}

		message('저장 되었습니다.');
		redirect($this->link->get(array('action'=>'write')));
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
		$this->title = '카테고리 추가';

		$id = $this->link->get_segment('id', FALSE);

		if( ! empty($id))
		{
			$model = array(
				'from' => 'bbs_category',
				'conditions' => array('where' => array('id' => $id)),
				'not_exists' => '_error_exists_category'
			);
			$data = $this->get_row($model);
		}
		else
		{
			$data = $this->set_default('bbs_category');
			$data['data']['tableid'] = $this->tableid;
			$data['data']['parent_id'] = $this->link->get_segment('parent_id', FALSE);
		}

		$view = array(
			'skin' => 'manager/popup/category_add',
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
		$this->form_validation->set_rules('tableid', '게시판 아이디', 'trim|required|alpha_numeric');
		$this->form_validation->set_rules('title', '카테고리 명', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->category_add();
			return FALSE;
		}

		$id = $this->input->post('id');

		if(empty($id))
		{
			$data['tableid'] = $this->input->post('tableid');
			$data['parent_id'] = $this->input->post('parent_id');

			$model = array(
				'select' => 'MAX(sort) as new_sort',
				'from' => 'bbs_category',
				'conditions' => array('where' => array('tableid' => $data['tableid']))
			);
			$row = $this->get_row($model);

			$data['sort'] = $row['data']['new_sort']+1;
		}

		$data['title'] = $this->input->post('title');

		$model = array(
			'data' => $data,
			'from' => 'bbs_category',
			'conditions' => ( ! empty($id)) ? array('where' => array('id' => $id)) : NULL,
			'update_cache' => sprintf('board_category_%s', $this->tableid)
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
	public function category_delete()
	{
		$tableid = $this->tableid;
		$id = $this->link->get_segment('id');

		$model = array(
			'from' => 'bbs_category',
			'order' => array('sort' => 'ASC'),
			'callback' => '_process_category'
		);
		$this->get_entries($model);

		if(isset($this->category[$tableid][$id]))
		{
			message('하위 카테고리가 존재하여 삭제 할 수 없습니다.', 'error');
			redirect($this->link->get(array('action'=>'write')));
		}

		$model = array(
			'from' => 'bbs_category',
			'conditions' => array('where' => array('id' => $id)),
			'update_cache' => sprintf('board_category_%s', $this->tableid)
		);
		$this->delete_row($model);

		message('삭제 되었습니다.');
		redirect($this->link->get(array('action'=>'write', 'id'=>NULL)));
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
		$tableid = $this->tableid;

		$model = array(
			'from' => 'bbs',
			'conditions' => array('where' => array('tableid' => $tableid))
		);
		$data = $this->get_entries($model);

		foreach($data['data'] as $item)
		{
			$model = array(
				'from' => 'comment',
				'conditions' => array('where' => array('pid' => $item['id']))
			);
			$this->delete_row($model);
		}

		$model = array(
			'from' => 'bbs',
			'conditions' => array('where' => array('tableid' => $tableid))
		);
		$this->delete_row($model);

		$model = array(
			'from' => 'bbs_category',
			'conditions' => array('where' => array('tableid' => $tableid)),
			'update_cache' => sprintf('board_category_%s', $tableid)
		);
		$this->delete_row($model);

		$model = array(
			'from' => 'bbs_setup',
			'conditions' => array('where' => array('tableid' => $tableid)),
			'update_cache' => sprintf('board_%s', $tableid)
		);
		$this->delete_row($model);

		message('삭제 되었습니다.');
		redirect($this->link->get(array('action'=>'index', 'tableid'=>NULL)));
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function _construct_category($tableid, $parent=0)
	{
		if(isset($this->category[$tableid][$parent]))
		{
			$data['data'] = $this->category[$tableid][$parent];
		}
		else
		{
			return FALSE;
		}

		$view = array(
			'skin' => 'manager/_category_item',
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
	protected function _process_entries(&$item)
	{
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
		$this->category[$item['tableid']][$item['parent_id']][] = $item;
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
		script('back', '게시판이 존재하지 않습니다.');
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
		script('back', '카테고리가 존재하지 않습니다.');
	}

}
