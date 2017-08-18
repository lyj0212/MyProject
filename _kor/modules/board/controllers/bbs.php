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
class Bbs extends PL_Controller {

	public $tableid;
	public $setup;
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
		$this->tableid = $this->link->get_segment('tableid');

		$model = array(
			'from' => 'bbs_setup',
			'conditions' => array('where' => array('tableid' => $this->tableid)),
			'not_exists' => '_error_exists_setup',
			'enable_cache' => sprintf('board_%s', $this->tableid)
		);
		$data = $this->get_row($model);

		if( ! empty($data['data']['list']))
		{
			$data['data']['list'] = unserialize($data['data']['list']);
		}
		$this->setup = $data['data'];

		$this->load->config('default');
		$this->list_object = config_item('list_section');

		$this->load->library('image_lib');
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
        //$this->cache->delete('module_config_order');

		$model = array(
			'select' => 'A.*, B.title as category_title',
			'from' => 'bbs A',
			'join' => array('bbs_category B', 'A.category=B.id', 'left'),
			'conditions' => array('where' => array('A.isnotice' => 'Y', 'A.tableid' => $this->tableid)),
			'order' => array('A.fid'=>'ASC', 'A.thread'=>'ASC'),
			'include_file' => TRUE,
			'include_readed' => TRUE,
			'callback' => '_process_entries',
			//'enable_cache' => array(sprintf('bbs_notice_%s', $this->tableid), 7200)
		);
		$data['notices'] = $this->get_entries($model);

		$model = array(
			'select' => 'A.*, B.title as category_title',
			'from' => 'bbs A',
			'join' => array('bbs_category B', 'A.category=B.id', 'left'),
			'conditions' => array('where' => array('A.tableid' => $this->tableid, 'A.isnotice' => 'N')),
			'limit' => 20,
			'paging' => TRUE,
			'paging_action' => 'index',
			'order' => array('A.fid'=>'ASC', 'A.thread'=>'ASC'),
			'include_file' => TRUE,
			'include_readed' => TRUE,
			'include_search' => TRUE,
			'callback' => '_process_entries'
		);

		if(get_field('category'))
		{
			$model['conditions']['where']['A.category'] = get_field('category');
		}

		$data['artices'] = $this->get_entries($model);

		if($this->setup['use_category'] == 'Y')
		{
			$model = array(
				'from' => 'bbs_category',
				'conditions' => array('where' => array('tableid' => $this->tableid)),
				'order' => array('sort' => 'ASC'),
				'enable_cache' => sprintf('board_category_%s', $this->tableid)
			);
			$data['category'] = $this->get_entries($model);
		}

		if($this->setup['type'] == 'gallery2')
		{
			$this->html['css'][] = './modules/board/css/blocksit.css';
			$this->html['js'][] = './modules/board/js/blocksit.min.js';
		}

		if($this->link->base['map'] == 'webAdmin') {
			$view = array(
				'skin' => sprintf('bbs/admin/%s', $this->setup['type']),
				'data' => $data	
			);
		}
		else 
		{
			$view = array(
				'skin' => sprintf('bbs/%s', $this->setup['type']),
				'data' => $data	
			);
		}

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
			'from' => 'bbs',
			'conditions' => array('where' => array('id' => $id, 'tableid' => $this->tableid)),
			'include_file' => TRUE,
			'update_readed'  => TRUE,
			'callback' => '_process_entries',
			'not_exists' => '_error_exists'
		);
		$data = $this->get_row($model);

		$hit = $this->session->userdata('hit');
		if(empty($hit))
		{
			$hit = array();
		}
		if( ! in_array($id, $hit))
		{
			$model = array(
				'from' => 'bbs',
				'conditions' => array('where' => array('id' => $id)),
				'data' => array('hit' => $data['data']['hit']+1),
				'include_time' => FALSE
			);
			$this->save_row($model);

			$hit[] = $id;
			$this->session->set_userdata('hit', $hit);
		}
		
		if($this->link->base['map'] == 'webAdmin') {
			$view = array(
				'skin' => 'bbs/admin/view',
				'data' => $data
			);
		}
		else 
		{
			$view = array(
				'skin' => 'bbs/view',
				'data' => $data
			);
		}
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
			$model = array(
				'from' => 'bbs',
				'conditions' => array('where' => array('id' => $id)),
				'include_file' => 'ALL',
				'not_exists' => '_error_exists'
			);
			$data = $this->get_row($model);
		}
		else
		{
			$data = $this->set_default('bbs');
		}

		if($this->setup['use_category'] == 'Y')
		{
			$model = array(
				'from' => 'bbs_category',
				'conditions' => array('where' => array('tableid' => $this->tableid)),
				'order' => array('sort' => 'ASC'),
				'enable_cache' => sprintf('board_category_%s', $this->tableid)
			);
			$data['category'] = $this->get_entries($model);
		}

		if($this->link->base['map'] == 'webAdmin') {
			$view = array(
				'skin' => 'bbs/admin/write',
				'data' => $data
			);
		}
		else 
		{
			$view = array(
				'skin' => 'bbs/write',
				'data' => $data
			);
		}
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

		if($this->form_validation->run() === FALSE)
		{
			$this->write();
			return FALSE;
		}

		$id = $this->input->post('id');
		$data['tableid'] = $this->tableid;
		$data['subject'] = $this->input->post('subject');
		$data['category'] = $this->input->post('category');
		$data['isnotice'] = ($this->input->post('isnotice') == 'Y') ? 'Y' : 'N';
		$data['issecret'] = ($this->input->post('issecret') == 'Y') ? 'Y' : 'N';
		$data['contents'] = $this->input->post('contents');
		$data['contents'] = preg_replace(sprintf("|src=[\"']?%s(.*?)[\"']|i", rtrim(site_url(), '/')), "src=\"$1\"", $data['contents']);
		$data['contents'] = preg_replace(sprintf("|href=[\"']?%s(.*?)[\"']|i", rtrim(site_url(), '/')), "href=\"$1\"", $data['contents']);

		if(empty($id))
		{
			$data['ismember'] = $this->account->get('id');
			$data['name'] = $this->account->get('name');

			$model = array(
				'select' => 'MIN(`fid`) as fid',
				'from' => 'bbs'
			);
			$row = $this->get_row($model);

			$fid = ($row['data']['fid'] < 0) ? $row['data']['fid']-1 : -1;

			$data['fid'] = $fid;
			$data['thread'] = 'AA';
			$data['ip'] = $this->input->ip_address();
		}

		$model = array(
			'from' => 'bbs',
			'conditions' => ( ! empty($id)) ? array('where'=>array('id'=>$id)) : NULL,
			'data' => $data,
			'include_file' => TRUE,
			'update_readed' => TRUE
		);
		$affect_id = $this->save_row($model);

		if($data['isnotice'] == 'Y')
		{
			$this->cache->delete(sprintf('bbs_notice_%s', $this->tableid));
		}

		message('저장 되었습니다.');
		redirect($this->link->get(array('action'=>'view', 'id'=>$affect_id)));
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
			'from' => 'bbs',
			'conditions' => array('where' => array('id' => $id)),
			'not_exists' => '_error_exists'
		);
		$data = $this->get_row($model);

		$model = array(
			'from' => 'bbs',
			'conditions' => array('where' => array('id' => $id))
		);
		$this->delete_row($model);

		$model = array(
			'from' => 'comment',
			'conditions' => array('where' => array('pid' => $id))
		);
		$this->delete_row($model);

		if($data['data']['isnotice'] == 'Y')
		{
			$this->cache->delete(sprintf('bbs_notice_%s', $this->tableid));
		}

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
	public function change_status()
	{
		$id = $this->link->get_segment('id');
		$status = $this->input->post('status');

		$model = array(
			'from' => 'bbs',
			'conditions' => array('where' => array('id' => $id)),
			'data' => array('status' => $status)
		);
		$this->save_row($model);

		echo 'ok';
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	protected function _process_entries(&$item, $data)
	{
		$item['hit'] = number_format($item['hit']);
		$item['total_comment'] = number_format($item['total_comment']);

		$width = 300;
		$height = 200;
		if(isset($data['files'][$item['id']][0]))
		{
			$file = $data['files'][$item['id']][0];
			$config = array(
				'source_image' => $file['upload_path'].$file['raw_name'].$file['file_ext'],
				'new_image' => sprintf('%sthumbs/%s_%s_%s%s', $file['upload_path'], $file['raw_name'], $width, $height, $file['file_ext']),
				'width' => $width,
				'height' => $height,
				'maintain_ratio' => ($this->setup['type'] == 'gallery2') ? TRUE : FALSE
			);
			$thumb = $this->image_lib->thumb($config);
		}
		else
		{
			$thumb = $this->image_lib->noimg($width, $height);
		}

		$item['thumb'] = site_url($thumb);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	protected function _error_exists_setup()
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
	protected function _error_exists()
	{
		script('back', '게시물이 존재하지 않습니다.');
	}

}
