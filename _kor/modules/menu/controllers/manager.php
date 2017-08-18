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
 * Menu Class
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Manager extends PL_Controller {

	public $menus = array();
	public $module_config = array();
	public $issuper = FALSE;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->issuper = ($this->menu->current['isadmin'] == 'Y') ? TRUE : FALSE;
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
		$this->title = '메뉴 관리';

		$model = array(
			'from' => 'menu',
			'order' => array('id' => 'ASC'),
			'hasmany' => array(
				'select' => 'A.*, B.map',
				'pkey' => 'id',
				'match' => 'menu_id',
				'from' => 'menu_item A',
				'join' => array('menu B', 'A.menu_id=B.id'),
				'conditions' => array(),
				'order' => array('A.sort' => 'ASC'),
				'return' => 'sub',
				'callback' => '_process_entries'
			)
		);

		if($this->issuper != TRUE)
		{
			$model['conditions']['where'] = array('COALESCE(map, \'\')=' => '');
			$model['hasmany']['conditions']['where'] = array('only_show_super' => 'N');
		}

		$data = $this->get_entries($model);

		$this->html['css'][] = '/_res/libs/sitemap/sitemap.css';
		$this->html['js'][] = '/_res/libs/sitemap/sitemap.js';

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
	public function add_map()
	{
		$data['title'] = '제목 없음';

		$this->title = '메뉴 추가';

		$id = $this->link->get_segment('id', FALSE);

		if( ! empty($id))
		{
			$model = array(
				'from' => 'menu',
				'conditions' => array('where' => array('id' => $id)),
				'not_exists' => '_error_map_exists'
			);
			$data = $this->get_row($model);
		}
		else
		{
			$data = $this->set_default('menu');
		}

		$view = array(
			'skin' => 'popup/add_map',
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
	public function save_map()
	{
		$id = $this->input->post('id');

		$this->form_validation->set_rules('title', '맵 이름', 'required');

		if(empty($id))
		{
			$this->form_validation->set_rules('map', '맵 구분자', 'trim|alpha_dash|is_unique[menu.map]');
		}
		else
		{
			$this->form_validation->set_rules('map', '맵 구분자', 'trim|alpha_dash');
		}

		if ($this->form_validation->run() === FALSE)
		{
			$this->add_map();
			return FALSE;
		}

		$data['title'] = $this->input->post('title');
		$data['map'] = $this->input->post('map');
		$data['isadmin'] = ($this->input->post('isadmin') == 'Y') ? 'Y' : 'N';

		if(empty($data['map']) AND empty($id))
		{
			$model = array(
				'from' => 'menu',
				'conditions' => array('where' => array('map' => ''))
			);
			$check = $this->check_exists($model);

			if($check == TRUE)
			{
				message('빈 맵 구분자를 이미 사용중 입니다.', 'error');
				$this->add_map();
				return FALSE;
			}
		}

		$model = array(
			'from' => 'menu',
			'conditions' => ( ! empty($id)) ? array('where' => array('id' => $id)) : NULL,
			'data' => $data
		);
		$affect_id = $this->save_row($model);

		$this->_rewrite_map_config();

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
	private function _rewrite_map_config()
	{
		$model = array(
			'select' => 'map',
			'from' => 'menu',
			'conditions' => array('where' => array('map !=' => ''))
		);
		$data = $this->get_entries($model);

		$map = array();
		foreach($data['data'] as $item)
		{
			$map[] = $item['map'];
		}

		$map_file = FCPATH.'attach/config/map.php';
		$map_string = "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
		$map_string .= implode("\n", $map);

		$this->load->helper('file');
		if( ! write_file($map_file, $map_string))
		{
			message(sprintf('파일 쓰기에 실패 하였습니다.<br />"%s" 퍼미션을 확인해 주세요.', $map_file), 'error');
			return FALSE;
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function save_area()
	{
		$this->form_validation->set_rules('menu_id', '맵 아이디', 'required|numeric');
		$this->form_validation->set_rules('title', '맵 이름', 'required');
		if ($this->form_validation->run() === FALSE)
		{
			$this->index();
			return FALSE;
		}

		$menu_id = $this->input->post('menu_id');
		$menu['title'] = $this->input->post('title');

		$model = array(
			'from' => 'menu',
			'conditions' => array('where' => array('id' => $menu_id)),
			'data'  => $menu
		);
		$this->save_row($model);

		$item_id = $this->input->post('item_id');
		$parent_id = $this->input->post('parent_id');

		foreach($item_id as $key => $id)
		{
			$model = array(
				'from' => 'menu_item',
				'conditions' => array('where' => array('id' => $id)),
				'data' => array(
					'parent_id' => $parent_id[$key],
					'sort' => $key+1
				)
			);
			$this->save_row($model);
		}

		message('저장 되었습니다.');
		redirect($this->link->get(array('action' => 'index')));
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function delete_map()
	{
		$menu_id = $this->link->get_segment('menu_id');

		$model = array(
			'from' => 'menu',
			'conditions' => array('where' => array('id' => $menu_id))
		);
		$this->delete_row($model);

		$model = array(
			'from' => 'menu_item',
			'conditions' => array('where' => array('menu_id' => $menu_id))
		);
		$this->delete_row($model);

		$this->_rewrite_map_config();

		message('삭제 되었습니다.');
		redirect($this->link->get(array('action' => 'index', 'menu_id' => NULL)));
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
		$this->title = '메뉴 추가';

		$id = $this->link->get_segment('id', FALSE);

		if( ! empty($id))
		{
			$model = array(
				'from' => 'menu_item',
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
			$data = $this->set_default('menu_item');
			$data['data']['menu_id'] = $this->link->get_segment('menu_id');
			$data['data']['parent_id'] = $this->link->get_segment('parent_id', FALSE);

			if(($prev_layout = $this->input->cookie('prev_layout')))
			{
				$data['data']['layout'] = $prev_layout;
			}

			$data['data']['continue'] = 'N';
			if(($continue = $this->link->get_segment('continue', FALSE)))
			{
				$data['data']['continue'] = 'Y';
			}
		}

		$model = array(
			'select' => 'map',
			'from' => 'menu',
			'conditions' => array('where' => array('id' => $data['data']['menu_id']))
		);
		$data['menu'] = $this->get_row($model);

		// 동적 셀렉트에서 사용하기 위해 정의
		if($this->input->post('param'))
		{
			$data['data']['param'] = $this->input->post('param');
		}

		$model = array(
			'from' => 'layouts'
		);
		$data['layout'] = $this->get_entries($model);

		$this->load->helper('directory');
		$map = directory_map('./modules/', 1);
		foreach($map as $module_name)
		{
			$file = sprintf('./modules/%s/conf/module.xml', $module_name);
			if(file_exists($file))
			{
				$conf = simplexml_load_file($file);

				$arr = object2array($conf);
				if(isset($arr['section']))
				{
					if( ! isset($arr['section'][0]))
					{
						$arr['section'] = array($arr['section']);
					}
				}
				else
				{
					continue;
				}

				// separation
				if( ! empty($arr['name']))
				{
					$data['module'][] = array('separation_start' => $arr['name']);
				}
				foreach($arr['section'] as $item)
				{
					if(isset($item['param']))
					{
						$item['child'] = TRUE;
					}
					$data['module'][] = $item;
				}
				if( ! empty($arr['name']))
				{
					$data['module'][] = array('separation_end' => TRUE);
				}
			}
		}

		$view = array(
			'skin' => 'popup/add',
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
		$id = $this->input->post('id');

		$this->form_validation->set_rules('menu_id', '맵 아이디', 'required|numeric');
		$this->form_validation->set_rules('title', '메뉴명', 'required');
		$this->form_validation->set_rules('url', '접속 URL', 'trim|alpha_dash');

		if ($this->form_validation->run() === FALSE)
		{
			$this->add();
			return FALSE;
		}

		$menu_id = $this->input->post('menu_id');
		$url = $this->input->post('url');

		if(empty($id))
		{
			if( ! empty($url))
			{
				$model = array(
					'from' => 'menu_item',
					'conditions' => array('where' => array('menu_id' => $menu_id, 'url' => $url))
				);
				$check = $this->check_exists($model);
				if($check == TRUE)
				{
					message('해당 URL은 이미 등록되어 있습니다.', 'error');
					$this->add();
					return FALSE;
				}
			}

			$data['menu_id'] = $menu_id;
			$data['parent_id'] = $this->input->post('parent_id');

			$model = array(
				'select' => 'MAX(sort) as new_sort',
				'from' => 'menu_item',
				'conditions' => array('where' => array('menu_id' => $data['menu_id']))
			);
			$row = $this->get_row($model);

			$data['sort'] = $row['data']['new_sort']+1;
		}

		$data['url'] = $url;
		$data['title'] = $this->input->post('title');
		$data['layout'] = $this->input->post('layout');
		$data['link'] = $this->input->post('link');
		$data['win_target'] = $this->input->post('win_target');
		$data['target'] = decode_url($this->input->post('target'));
		$data['param'] = $this->input->post('param');
		$data['hidden'] = ($this->input->post('hidden') == 'Y') ? 'Y' : 'N';
		$data['index'] = ($this->input->post('index') == 'Y') ? 'Y' : 'N';

		$model = array(
			'from' => 'menu_item',
			'conditions' => ( ! empty($id)) ? array('where' => array('id' => $id)) : NULL,
			'data' => $data,
			'include_file' => TRUE
		);
		$affect_id = $this->save_row($model);

		if($this->input->post('layout_apply'))
		{
			$model = array(
				'from' => 'menu_item',
				'conditions' => array('where' => array('menu_id' => $menu_id)),
				'data' => array('layout' => $data['layout'])
			);
			$this->save_row($model);
		}

		$cookie = array(
			'name' => 'prev_layout',
			'value' => $data['layout'],
			'expire' => 865000
		);
		$this->input->set_cookie($data);

		if($this->input->post('continue'))
		{
			message('저장 되었습니다.');
			redirect($this->link->get(array('action' => 'add', 'continue'=>'y')));
		}
		else
		{
			message('저장 되었습니다.');
			script('parentreload');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function change_visible()
	{
		$id = $this->link->get_segment('id');
		$visible = $this->link->get_segment('visible');

		$model = array(
			'from' => 'menu_item',
			'conditions' => array('where' => array('id' => $id)),
			'data' => array('hidden' => $visible)
		);
		$this->save_row($model);

		message('저장 되었습니다.');
		redirect($this->link->get(array('action'=>'index', 'id'=>NULL,'visible'=>NULL)));
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
			'from' => 'menu_item',
			'conditions' => array('where' => array('id' => $id))
		);
		$this->delete_row($model);

		$model = array(
			'from' => 'permission',
			'conditions' => array('where' => array('menu_id' => $id))
		);
		$this->delete_row($model);

		message('삭제 되었습니다.');
		redirect($this->link->get(array('action' => 'index', 'id' => NULL)));
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function _construct_html($menu_id, $parent=0)
	{
		if(isset($this->menus[$menu_id][$parent]))
		{
			$data['data'] = $this->menus[$menu_id][$parent];
		}
		else
		{
			return FALSE;
		}

		$view = array(
			'skin' => 'menus_item',
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
	public function get_param()
	{
		$target = decode_url($this->link->get_segment('target'));
		list($module_name) = explode('/', $target);

		$file = sprintf('./modules/%s/conf/module.xml', $module_name);
		if(file_exists($file))
		{
			$conf = simplexml_load_file($file);

			$arr = object2array($conf);
			if(isset($arr['section']))
			{
				if( ! isset($arr['section'][0]))
				{
					$arr['section'] = array($arr['section']);
				}
			}
			else
			{
				return FALSE;
			}

			foreach($arr['section'] as $item)
			{
				if($item['target'] == $target)
				{
					$params = $item['param'];
					if( ! empty($params['sql']))
					{
						$params['sql'] = str_replace('{dbprefix}', $this->db->dbprefix, $params['sql']);
						$group = $this->get_query($params['sql']);

						$data = array();
						foreach($group as $key=>$item)
						{
							$data['group'][$key]['@attributes'] = $item;
						}
						echo json_encode($data);
					}
					elseif( ! empty($params['group']))
					{
						echo json_encode($params);
					}
					else
					{
						echo json_encode(array('failed' => ''));
					}
					break;
				}
			}
		}
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
		static $xml = array();
		static $module_config = array();

		$item['grant_option'] = FALSE;
		if(isset($module_config[$item['target']]))
		{
			$item['grant_option'] = $module_config[$item['target']];
		}
		else
		{
			list($module) = explode('/', $item['target']);
			if( ! empty($xml[$module]))
			{
				$arr = $xml[$module];
			}
			else
			{
				$file = sprintf('./modules/%s/conf/module.xml', $module);
				if(file_exists($file))
				{
					$conf = simplexml_load_file($file);
					$arr = $xml[$module] = object2array($conf);
				}
			}

			if( ! empty($arr))
			{
				if(isset($arr['section']))
				{
					if( ! isset($arr['section'][0]))
					{
						$arr['section'] = array($arr['section']);
					}
					foreach($arr['section'] as $section)
					{
						if($section['target'] == $item['target'])
						{
							if(isset($section['grants']['grant']))
							{
								foreach($section['grants']['grant'] as $grant)
								{
									if(empty($grant['@attributes']['setup']) OR $grant['@attributes']['setup'] != 'N')
									{
										$item['grant_option'] = $module_config[$item['target']] = TRUE;
										break;
									}
								}
							}
							else
							{
								$module_config[$item['target']] = FALSE;
							}
						}
					}
				}
				else
				{
					$module_config[$item['target']] = FALSE;
				}
			}
			else
			{
				$module_config[$item['target']] = FALSE;
			}
		}

		$this->menus[$item['menu_id']][$item['parent_id']][] = $item;
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
		script('back', '메뉴가 존재하지 않습니다.');
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	protected function _error_map_exists()
	{
		script('back', '맵이 존재하지 않습니다.');
	}

}