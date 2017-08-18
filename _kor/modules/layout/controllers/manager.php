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
 * Layout Class
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

		$this->load->helper('file');
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
			'from' => 'layouts',
			'limit' => 20,
			'paging' => TRUE,
			'include_company' => FALSE
		);
		$data = $this->get_entries($model);

		$view = array(
			'skin' => 'index',
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
			$model = array(
				'from' => 'layouts',
				'conditions' => array('where' => array('id' => $id)),
				'include_company' => FALSE,
				'callback' => '_process_entries',
				'not_exists' => '_error_exists'
			);
			$data = $this->get_row($model);
		}
		else
		{
			$data = $this->set_default('layouts');
		}

		$this->html['css'][] = '/_res/libs/codemirror/codemirror.css';
		$this->html['js'][] = '/_res/libs/codemirror/codemirror.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/xml/xml.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/javascript/javascript.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/css/css.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/clike/clike.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/php/php.js';

		$view = array(
			'skin' => 'write',
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
		$this->form_validation->set_rules('title', '레이아웃 이름', 'trim|required');
		$this->form_validation->set_rules('source', 'HTML', 'trim|required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->write();
			return FALSE;
		}

		$id = $this->input->post('id');
		$source = $this->input->post('source', FALSE);
		$top_menu = $this->input->post('top_menu', FALSE);
		$top_menu_depth = $this->input->post('top_menu_depth', FALSE);
		$sub_menu = $this->input->post('sub_menu', FALSE);
		$sub_menu_depth = $this->input->post('sub_menu_depth', FALSE);
		$popup = $this->input->post('popup', FALSE);

		$path = 'attach/layout';
		$path_menu = 'attach/layout/menu';
		$path_popup = 'attach/layout/popup';

		$create_new_source = TRUE;
		$create_new_top_menu = TRUE;
		$create_new_top_menu_depth = TRUE;
		$create_new_sub_menu = TRUE;
		$create_new_sub_menu_depth = TRUE;
		$create_new_popup = TRUE;

		if( ! empty($id))
		{
			$model = array(
				'from' => 'layouts',
				'conditions' => array('where' => array('id' => $id)),
				'include_company' => FALSE,
				'not_exists' => '_error_exists'
			);
			$row = $this->get_row($model);

			$source_path = $row['data']['source'];
			$top_menu_path = $row['data']['top_menu'];
			$top_menu_depth_path = $row['data']['top_menu_depth'];
			$sub_menu_path = $row['data']['sub_menu'];
			$sub_menu_depth_path = $row['data']['sub_menu_depth'];
			$popup_path = $row['data']['popup'];

			if(dirname($source_path) == $path)
			{
				$create_new_source = FALSE;
			}
			if(dirname($top_menu_path) == $path_menu)
			{
				$create_new_top_menu = FALSE;
			}
			if(dirname($top_menu_depth_path) == $path_menu)
			{
				$create_new_top_menu_depth = FALSE;
			}
			if(dirname($sub_menu_path) == $path_menu)
			{
				$create_new_sub_menu = FALSE;
			}
			if(dirname($sub_menu_depth_path) == $path_menu)
			{
				$create_new_sub_menu_depth = FALSE;
			}
			if(dirname($popup_path) == $path_popup)
			{
				$create_new_popup = FALSE;
			}
		}

		$this->load->helper('string');

		if($create_new_source == TRUE)
		{
			$filename = random_string('alpha', 15);
			$source_path = sprintf('%s/%s.source', $path, $filename);
		}

		if($create_new_top_menu == TRUE)
		{
			$filename = random_string('alpha', 15);
			$top_menu_path = sprintf('%s/%s.source', $path_menu, $filename);
		}

		if($create_new_top_menu == TRUE)
		{
			$filename = random_string('alpha', 15);
			$top_menu_depth_path = sprintf('%s/%s.source', $path_menu, $filename);
		}

		if($create_new_top_menu == TRUE)
		{
			$filename = random_string('alpha', 15);
			$sub_menu_path = sprintf('%s/%s.source', $path_menu, $filename);
		}

		if($create_new_top_menu == TRUE)
		{
			$filename = random_string('alpha', 15);
			$sub_menu_depth_path = sprintf('%s/%s.source', $path_menu, $filename);
		}

		if($create_new_popup == TRUE)
		{
			$filename = random_string('alpha', 15);
			$popup_path = sprintf('%s/%s.source', $path_popup, $filename);
		}

		if( ! is_dir($path))
		{
			mkdir($path, 0777, TRUE);
		}

		if( ! is_dir($path_menu))
		{
			mkdir($path_menu, 0777, TRUE);
		}

		if( ! is_dir($path_popup))
		{
			mkdir($path_popup, 0777, TRUE);
		}

		if( ! write_file($source_path, $source))
		{
			message('파일 쓰기에 실패 하였습니다.', 'error');
			$this->write();
			return FALSE;
		}

		if( ! write_file($top_menu_path, $top_menu))
		{
			message('파일 쓰기에 실패 하였습니다.', 'error');
			$this->write();
			return FALSE;
		}

		if( ! write_file($top_menu_depth_path, $top_menu_depth))
		{
			message('파일 쓰기에 실패 하였습니다.', 'error');
			$this->write();
			return FALSE;
		}

		if( ! write_file($sub_menu_path, $sub_menu))
		{
			message('파일 쓰기에 실패 하였습니다.', 'error');
			$this->write();
			return FALSE;
		}

		if( ! write_file($sub_menu_depth_path, $sub_menu_depth))
		{
			message('파일 쓰기에 실패 하였습니다.', 'error');
			$this->write();
			return FALSE;
		}

		// 팝업은 내용이 없으면 경로를 기록하지 않는다. for popup layout session
		if( ! empty($popup))
		{
			if( ! write_file($popup_path, $popup))
			{
				message('파일 쓰기에 실패 하였습니다.', 'error');
				$this->write();
				return FALSE;
			}
		}

		$data['title'] = $this->input->post('title');
		$data['source'] = $source_path;
		$data['top_menu'] = $top_menu_path;
		$data['top_menu_depth'] = $top_menu_depth_path;
		$data['sub_menu'] = $sub_menu_path;
		$data['sub_menu_depth'] = $sub_menu_depth_path;

		if( ! empty($popup))
		{
			$data['popup'] = $popup_path;
		}

		$model = array(
			'data' => $data,
			'from' => 'layouts',
			'conditions' => ( ! empty($id)) ? array('where'=>array('id'=>$id)) : NULL,
			'include_company' => FALSE
		);
		$affect_id = $this->save_row($model);

		$this->cache->delete(sprintf('layout_%s', $affect_id));

		message('저장 되었습니다.');
		redirect($this->link->get(array('action'=>'write', 'id'=>$affect_id)));
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
			'from' => 'layouts',
			'conditions' => array('where' => array('id' => $id)),
			'include_company' => FALSE,
			'not_exists' => '_error_exists'
		);
		$row = $this->get_row($model);

		$model = array(
			'from' => 'layouts',
			'conditions' => array('where'=>array('id'=>$id)),
			'include_company' => FALSE
		);
		$this->delete_row($model);

		@unlink($row['data']['source']);
		@unlink($row['data']['top_menu']);
		@unlink($row['data']['top_menu_depth']);
		@unlink($row['data']['sub_menu']);
		@unlink($row['data']['sub_menu_depth']);
		@unlink($row['data']['popup']);

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
	protected function _process_entries(&$item)
	{
		$item['source'] = read_file($item['source']);
		$item['top_menu'] = read_file($item['top_menu']);
		$item['top_menu_depth'] = read_file($item['top_menu_depth']);
		$item['sub_menu'] = read_file($item['sub_menu']);
		$item['sub_menu_depth'] = read_file($item['sub_menu_depth']);
		$item['popup'] = read_file($item['popup']);
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
		script('back', '레이아웃이 존재하지 않습니다.');
	}

}
