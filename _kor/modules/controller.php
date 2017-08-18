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

// ------------------------------------------------------------------------

/**
 * PL_Controller Class
 *
 * 모듈 기본 클래스
 *
 * @package	Plani Module
 * @subpackage	 Libraries
 * @category	Libraries
 * @author	Shin Donguk
 */
abstract class PL_Controller extends CI_Controller {

	public $title = '';
	public $layout = '';
	public $view_data = array();
	public $html = array(
		'js'=>array(),
		'css'=>array()
	);
    private $connected_db = array();
    public $current_active_db = 'default';

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$class = str_replace($this->config->item('controller_suffix'), '', get_class($this));
		Modules::$registry[strtolower($class)] = $this;
        
        $this->connected_db['default'] = $this->db;

		// cache enable in Menu library

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');

		$this->html['css'][] = '/_res/font-awesome/css/font-awesome.min.css';
		$this->html['css'][] = '/_res/libs/jquery/jquery-ui.css';
		$this->html['css'][] = '/_res/libs/colorbox/colorbox.css';
		$this->html['css'][] = '/_res/libs/datetimepicker/css/bootstrap-datetimepicker.css';
		$this->html['css'][] = '/_res/fontello/css/fontello.css';

		$this->html['js'][] = '/_res/libs/bootstrap/js/bootstrap.min.js';
		$this->html['js'][] = '/_res/libs/bootstrap/js/bootstrap-typeahead.js';
		$this->html['js'][] = '/_res/libs/cookie/jquery.cookie.js';
		$this->html['js'][] = '/_res/libs/colorbox/jquery.colorbox.js';
		$this->html['js'][] = '/_res/libs/validate/jquery.validate.min.js';
		$this->html['js'][] = '/_res/libs/placeholder/jquery.placeholder.min.js';
		$this->html['js'][] = '/_res/libs/desktop-notify/desktop-notify.js';
		$this->html['js'][] = '/_res/libs/datetimepicker/js/bootstrap-datetimepicker.js';
		$this->html['js'][] = '/_res/libs/datetimepicker/js/locales/bootstrap-datetimepicker.ko.js';
		$this->html['js'][] = '/_res/libs/number_format/jquery.number_format.js';
		$this->html['js'][] = '/_res/js/common.js';
		//$this->html['js'][] = '/js/script_common.js';
	}



	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	mixed
	 */
	public function _remap($method)
	{
		if(method_exists($this, $method) == TRUE)
		{
			if($this->auth->check() != TRUE)
			{
				$this->_show_permission_error();
			}
			else
			{
				$this->$method();
			}
		}
		else
		{
			show_404();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	mixed
	 */
	private function _get_caller_method()
	{
		$traces = debug_backtrace();

		if(isset($traces[2]))
		{
			return $traces[2]['function'];
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @access	private
	 * @param	array	initialization parameters
	 * @return	void
	 */
	public function _set(&$option, $params = array())
	{
		if(count($params) > 0)
		{
			foreach($params as $key => $val)
			{
				$option[$key] = $val;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	mixed
	 */
	protected function _show_permission_error($message='사용 권한이 없습니다.')
	{
		if(is_ajax())
		{
			exit($message);
		}
		else
		{
			$view = array(
				'skin' => 'error/no_permission',
				'data' => array('message' => $message)
			);
			$this->show($view);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	mixed
	 */
	protected function show($params)
	{
		$option['skin'] = '';
		$option['layout'] = '';
		$option['data'] = array();
		$option['return'] = FALSE;

		$this->_set($option, $params);

		if(empty($this->layout))
		{
			$this->layout = $option['layout'];
		}

		foreach($_POST as $key => $value)
		{
			if(stripos($key, 'dynamic_') === 0 OR stripos($key, 'dynamicid_') === 0)
			{
				foreach($value as $sort => $item)
				{
					$option['data'][$key][$sort] = $item;
				}
			}

			$option['data']['POST'][$key] = $value;
		}

		list($path, $_view) = Modules::find($option['skin'], $this->router->fetch_module(), 'views/');
		if(file_exists($js_file = sprintf('%sjavascript/%s%s', $path, basename($_view, EXT), EXT)))
		{
			$script_data = array($js_file, $option['data']);
			$this->view_data[] = $script_data;
		}

		list($path, $_view) = Modules::find('style.css', $this->router->fetch_module(), 'css/');
		if(file_exists(sprintf('./%s%s', $path, $_view)))
		{
			$this->html['css'][] = sprintf('/%s%s', $path, $_view);
		}

		$output = $this->load->view($option['skin'], $option['data'], $option['return']);

		if($option['return'] == TRUE)
		{
			return $output;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	mixed
	 */
	private function _set_conditions_string($conditions, $table_prefix='')
	{
		$conditions_string = array();
		if(is_string($conditions))
		{
			$conditions = array($conditions);
		}
		foreach($conditions as $type => $value)
		{
			switch(strtolower($type))
			{
				case 'where' :
					foreach($value as $key => $val)
					{
						if(is_array($val))
						{
							$condition = array('where' => $val);
							$conditions_string[] = $this->_set_conditions_string($condition, $table_prefix);
						}
						elseif( ! empty($key))
						{
							$key = $this->_add_table_prefix($key, $table_prefix);
							if( ! $this->db->_has_operator($key))
							{
								$conditions_string[] = sprintf('%s = %s', $key, $this->db->escape($val));
							}
							else
							{
								$conditions_string[] = sprintf('%s %s', $key, $this->db->escape($val));
							}
						}
						else
						{
							$conditions_string[] = $val;
						}
					}
					break;
				case 'where_in' :
					foreach($value as $key => $val)
					{
					$key = $this->_add_table_prefix($key, $table_prefix);
						$conditions_string[] = sprintf('%s IN (\'%s\')', $key, implode('\', \'', $val));
					}
					break;
				case 'like' :
					foreach($value as $key => $val)
					{
						$key = $this->_add_table_prefix($key, $table_prefix);
						$side = 'both';
						if(is_array($val) AND isset($val[1]) AND in_array(strtolower($val[1]), array('before', 'after', 'both')))
						{
							$side = strtolower($val[1]);
						}
							switch($side)
							{
								case 'before' :
									$conditions_string[] = sprintf('%s LIKE \'%%%s\'', $key, $this->db->escape_like_str($val[0]));
									break;
								case 'after' :
									$conditions_string[] = sprintf('%s LIKE \'%s%%\'', $key, $this->db->escape_like_str($val[0]));
									break;
								case 'both' :
									$conditions_string[] = sprintf('%s LIKE \'%%%s%%\'', $key, $this->db->escape_like_str($val[0]));
									break;
							}
					}
					break;
				case 'not_like' :
					foreach($value as $key => $val)
					{
						$key = $this->_add_table_prefix($key, $table_prefix);
						$side = 'both';
						if(is_array($val) AND isset($val[1]) AND in_array(strtolower($val[1]), array('before', 'after', 'both')))
						{
							$side = strtolower($val[1]);
						}
							switch($side)
							{
								case 'before' :
									$conditions_string[] = sprintf('%s NOT LIKE \'%%%s\'', $key, $this->db->escape_like_str($val[0]));
									break;
								case 'after' :
									$conditions_string[] = sprintf('%s NOT LIKE \'%s%%\'', $key, $this->db->escape_like_str($val[0]));
									break;
								case 'both' :
									$conditions_string[] = sprintf('%s NOT LIKE \'%%%s%%\'', $key, $this->db->escape_like_str($val[0]));
									break;
							}
					}
					break;
				case 'and' :
					$and_string = '(';
					$and_string .= $this->_set_conditions_string($value, $table_prefix);
					$and_string .= ')';
					$conditions_string[] = $and_string;
					break;
				case 'or' :
					$and_string = 'OR (';
					$and_string .= $this->_set_conditions_string($value, $table_prefix);
					$and_string .= ')';
					$conditions_string[] = $and_string;
					break;
				default :
					if(is_string($value))
					{
						$conditions_string[] = $value;
					}
			}
		}

		$result_string = str_ireplace('(DIVIDER) OR', 'OR', implode(' (DIVIDER) ', $conditions_string));
		$result_string = ltrim($result_string, 'OR ');

		return $result_string;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	mixed
	 */
	private function _set_conditions($conditions, $table_prefix='')
	{
		$conditions_string = $this->_set_conditions_string($conditions, $table_prefix);
		$conditions_array = explode(' (DIVIDER) ', $conditions_string);

		foreach($conditions_array as $string)
		{
			$this->db->where($string, NULL, FALSE);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	mixed
	 */
	private function _add_table_prefix($key, $table_prefix='')
	{
		$key = trim($key);

		if( ! empty($table_prefix) AND strpos($key, '.') === FALSE AND $this->db->_has_operator($key) == FALSE)
		{
			return sprintf('%s.%s', $table_prefix, $key);
		}
		else
		{
			return $key;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	mixed
	 */
	private function _reset_run($ar_reset_items)
	{
		foreach ($ar_reset_items as $item => $default_value)
		{
			if ( ! in_array($item, $this->db->ar_store_array))
			{
				$this->db->$item = $default_value;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	mixed
	 */
	private function _reset_select()
	{
		$ar_reset_items = array(
			'ar_select'			=> array(),
			'ar_from'			=> array(),
			'ar_join'			=> array(),
			'ar_where'			=> array(),
			'ar_like'			=> array(),
			'ar_groupby'		=> array(),
			'ar_having'			=> array(),
			'ar_orderby'		=> array(),
			'ar_wherein'		=> array(),
			'ar_aliased_tables'	=> array(),
			'ar_no_escape'		=> array(),
			'ar_distinct'		=> FALSE,
			'ar_limit'			=> FALSE,
			'ar_offset'			=> FALSE,
			'ar_order'			=> FALSE,
		);

		$this->_reset_run($ar_reset_items);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function get_entries($params, $parent_data=array())
	{
		$option['select'] = '*';
		$option['from'] = '';
		$option['join'] = '';
		$option['conditions'] = array();
		$option['order'] = array();
		$option['group'] = '';
		$option['limit'] = '';
		$option['paging'] = FALSE;
		$option['paging_action'] = $this->_get_caller_method();
		$option['return'] = 'data';
		$option['hasmany'] = array();
		$option['callback_object'] = $this;
		$option['callback'] = '';
		$option['include_file'] = FALSE;
		$option['include_dynamic'] = FALSE;
		$option['include_category'] = FALSE;
		$option['include_readed'] = FALSE;
		$option['update_readed'] = FALSE;
		$option['include_search'] = FALSE;
		$option['enable_cache'] = FALSE;

		$this->_set($option, $params);

		// 캐쉬 적용
		if( ! empty($option['enable_cache']))
		{
			if(is_array($option['enable_cache']))
			{
				$cache_name = $option['enable_cache'][0];
				$cache_expire = $option['enable_cache'][1];
			}
			else
			{
				$cache_name = $option['enable_cache'];
				$cache_expire = 9999999;
			}
		}

		if(empty($option['enable_cache']) OR ! empty($option['include_search']) OR ($data = $this->cache->get($cache_name)) === FALSE)
		{
			$this->db->start_cache();

			$table = explode(' ', $option['from']);
			$table_prefix = array_pop($table);

			// select
			if( ! is_array($option['select']))
			{
				$option['select'] = array($option['select']);
			}
			foreach($option['select'] as $value)
			{
				$escape = TRUE;
				if(is_array($value))
				{
					$escape = $value[1];
					$value = $value[0];
				}
				$select_array = explode(',', $value);
				foreach($select_array as $field)
				{
					if($escape != FALSE)
					{
						$field = $this->_add_table_prefix($field, $table_prefix);
					}
					$this->db->select($field, $escape);
				}
			}

			// from
			$this->db->from($option['from']);

			// join
			if( ! empty($option['join']))
			{
				if( ! is_array($option['join'][0]))
				{
					$option['join'] = array($option['join']);
				}
				foreach($option['join'] as $item)
				{
					$this->db->join($item[0], $item[1], (isset($item[2])) ? $item[2] : NULL);
				}
			}

			if(is_array($option['include_category']))
			{
				foreach($option['include_category'] as $field => $target)
				{
					$this->db->select(sprintf('%s.title as %s_title', $target, $field));
					$this->db->select(sprintf('%s.sort as %s_sort', $target, $field));
					$this->db->join(sprintf('global_category %s', $target), sprintf('%s.%s=%s.id', $table_prefix, $field, $target), 'left');
				}
			}

			if($option['include_readed'] == TRUE)
			{
				$this->db->select('readedtable.readed as isreaded, readedtable.is_highlight as ishighlight');
				$this->db->join('readed readedtable', sprintf('%s.id=readedtable.pid AND readedtable.member_id=\'%s\'', $table_prefix, $this->account->get('id')), 'left');

				// 댓글은 예외
				if($option['from'] != 'comment')
				{
					// 중요 게시물
					if(get_field('highlight') == '2')
					{
						$this->db->where('readedtable.is_highlight', 'Y');
					}

					// 새 게시물
					if(get_field('highlight') == '3')
					{
						$this->db->where('readedtable.readed IS NULL');
					}
				}
			}

			// searach
			if($option['include_search'] == TRUE)
			{
				$search_field = get_field('search_field');
				$search_keyword = get_field('search_keyword');
				$_field = explode('+', $search_field);
				$_field = array_filter($_field);

				$conditions = array();
				$search_query = array();
				if( ! empty($search_keyword) AND ! empty($_field))
				{
					foreach($_field as $field)
					{
						if($field == 'to')
						{
							CONTINUE;
						}
						if($field == 'comment')
						{
							$conditions[] = sprintf('(SELECT COUNT(*) FROM %scomment comment WHERE comment.company_id=A.company_id AND comment.pid=%s.id AND comment.contents LIKE "%%%s%%") > 0', $this->db->dbprefix, $table_prefix, $this->db->escape_like_str($search_keyword));
						}
						else
						{
							$conditions[] = sprintf('%s LIKE "%%%s%%"', $field, $this->db->escape_like_str($search_keyword));
						}
					}

					if( ! empty($conditions))
					{
						$search_query = array(sprintf('(%s)', implode(' OR ', $conditions)));
					}

					if( ! empty($search_query))
					{
						if( ! empty($option['conditions']['where']))
						{
							$option['conditions']['where'] = array_merge($option['conditions']['where'] , array($search_query));
						}
						else
						{
							$option['conditions']['where'] = $search_query;
						}
					}
				}
			}

			// conditions
			if( ! empty($option['conditions']))
			{
				$this->_set_conditions($option['conditions'], $table_prefix);
			}

			if( ! empty($option['order']))
			{
				if(is_array($option['order']))
				{
					foreach($option['order'] as $column => $options)
					{
						if(is_array($options))
						{
							$this->db->order_by($column, $options[0], $options[1]);
						}
						else
						{
							$this->db->order_by($column, $options);
						}
					}
				}
				else
				{
					$this->db->order_by($option['order']);
				}
			}

			$this->db->stop_cache();

			if( ! empty($option['group']))
			{
				if(is_array($option['group']))
				{
					foreach($option['group'] as $options)
					{
						$this->db->group_by($options);
					}
				}
				else
				{
					$this->db->group_by($option['group']);
				}
			}

			$paging = FALSE;
			if($option['paging'] == TRUE AND ! empty($option['limit']))
			{
				$paging = TRUE;
				$page = (is_numeric($this->link->get_segment('page', FALSE))) ? $this->link->get_segment('page') : 1;
				$query = $this->db->get(NULL, $option['limit'], ($page-1) * $option['limit']);
			}
			elseif( ! empty($option['limit']))
			{
				$query = $this->db->get(NULL, $option['limit']);
			}
			else
			{
				$query = $this->db->get();
			}

			$data[$option['return']] = $query->result_array();

			if($paging == TRUE)
			{
				$this->db->ar_cache_select = array();
				$this->db->select('COUNT(*) as total_cnt');

				$delete_left_join = TRUE;
				foreach($this->db->ar_cache_where as $cache_where)
				{
					if(stripos($cache_where, 'OR') !== FALSE)
					{
						$delete_left_join = FALSE;
						break;
					}
				}
				if($delete_left_join == TRUE)
				{
					foreach($this->db->ar_cache_join as $key => $cache_join)
					{
						if(strpos($cache_join, sprintf('LEFT JOIN `%smember`', $this->db->dbprefix)) !== 0 AND strpos($cache_join, sprintf('LEFT JOIN `%sreaded`', $this->db->dbprefix)) !== 0 AND strpos($cache_join, sprintf('LEFT JOIN `%scompany_interest`', $this->db->dbprefix)) !== 0 AND strpos($cache_join, 'LEFT JOIN') === 0)
						{
							unset($this->db->ar_cache_join[$key]);
						}
					}
				}

				$query = $this->db->get();
				$total_cnt = $query->row_array();
				$data['total_cnt'] = $total_cnt['total_cnt'];

				$this->load->library('pagenav');
				$proc_paging = $this->pagenav->get($data['total_cnt'], $option['limit'], $option['paging_action']);
				$data['paging_element'] = $this->load->view('common/paging', $proc_paging, TRUE);

				$n = $data['total_cnt']-($option['limit']*($page-1));
				foreach($data[$option['return']] as &$row)
				{
					$row['_num'] = $n--;
				}
			}

			$this->db->flush_cache();

			if(( ! empty($option['hasmany']) AND is_array($option['hasmany'])) OR $option['include_file'] != FALSE OR $option['include_dynamic'] != FALSE)
			{
				$hasmany_array = array();
				if( ! empty($option['hasmany']) AND ! isset($option['hasmany'][0]['from']))
				{
					$hasmany_array = array($option['hasmany']);
				}
				else
				{
					$hasmany_array = $option['hasmany'];
				}

				if($option['include_file'] != FALSE)
				{
					$hasmany_array[] = array(
						'pkey' => 'id',
						'match' => 'pid',
						'from' => 'files',
						'conditions' => (strtoupper($option['include_file']) != 'ALL') ? array('where' => array('use' => 'Y')) : array(),
						'order' => array('id' => 'ASC'),
						'callback' => '_process_file_entries',
						'return' => 'files'
					);
				}

				if($option['include_dynamic'] != FALSE)
				{
					$hasmany_array[] = array(
						'pkey' => 'id',
						'match' => 'pid',
						'from' => 'variable',
						'order' => array('sort' => 'ASC'),
						'return' => 'dynamic'
					);
				}

				foreach($hasmany_array as $item)
				{
					$pkey = (isset($item['pkey'])) ? $item['pkey'] : 'id';
					$match = (isset($item['match'])) ? $item['match'] : 'pid';
					$return = (isset($item['return'])) ? $item['return'] : 'subdata';

					$primary_key = array();
					foreach($data[$option['return']] as $parent)
					{
						if(isset($parent[$pkey]))
						{
							$primary_key[] = $parent[$pkey];
						}
					}
					$primary_key = array_unique($primary_key);

					if( ! empty($primary_key))
					{
						$item['conditions'] = array_merge_recursive(array('where_in' => array($match => $primary_key)), ( ! empty($item['conditions'])) ? $item['conditions'] : array());
						$item['return'] = $return;
						$hasmany = $this->get_entries($item, $option);
						foreach($hasmany[$return] as $_row)
						{
							$data[$return][$_row[$match]][] = $_row;

							if( ! empty($data['dynamic'][$_row[$match]]))
							{
								foreach($data['dynamic'][$_row[$match]] as $item)
								{
									$data[sprintf('dynamicid_%s', $item['variable'])][$_row[$match]][$item['sort']] = $item['id'];
									$data[sprintf('dynamic_%s', $item['variable'])][$_row[$match]][$item['sort']] = $item['value'];
								}
							}

						}
					}
				}
			}

			if( ! empty($option['enable_cache']))
			{
				$this->cache->save($cache_name, $data, $cache_expire);
			}
		}

		if( ! empty($option['callback']) AND is_callable(array($option['callback_object'], $option['callback'])))
		{
			foreach($data[$option['return']] as &$item)
			{
				call_user_func_array(array($option['callback_object'], $option['callback']), array(&$item, &$data));
			}
			unset($item);
		}

		if($option['update_readed'] == TRUE)
		{
			$pid = array();
			foreach($data[$option['return']] as $item)
			{
				$pid[] = $item['id'];
			}

			if( ! empty($pid))
			{
				$model = array(
					'select' => 'pid, readed',
					'from' => 'readed',
					'conditions' => array('where' => array('member_id' => $this->account->get('id')), 'where_in' => array('pid' => $pid))
				);
				$readed_data = $this->get_entries($model, $option);

				$readed_pid = array();
				$readed_readed = array();
				foreach($readed_data['data'] as $item)
				{
					$readed_pid[] = $item['pid'];
					$readed_readed[$item['pid']] = $item['readed'];
				}

				foreach($pid as $value)
				{
					if( ! in_array($value, $readed_pid))
					{
						$model = array(
							'from' => 'readed',
							'data' => array('pid' => $value, 'member_id' => $this->account->get('id'), 'readed' => date('Y-m-d H:i:s'))
						);
						$this->save_row($model);
					}
					elseif(empty($readed_readed[$value]))
					{
						$model = array(
							'from' => 'readed',
							'conditions' => array('where' => array('pid' => $value, 'member_id' => $this->account->get('id'))),
							'data' => array('readed' => date('Y-m-d H:i:s'))
						);
						$this->save_row($model);
					}
				}
			}
		}

		// 재귀 호출시 부모 파라미터가 바뀌므로 재정의
		if(is_array($parent_data))
		{
			$this->_set($parent_data);
		}

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function get_row($params)
	{
		$option['select'] = '*';
		$option['from'] = '';
		$option['join'] = '';
		$option['conditions'] = array();
		$option['group'] = '';
		$option['return'] = 'data';
		$option['hasmany'] = array();
		$option['callback_object'] = $this;
		$option['callback'] = '';
		$option['not_exists'] = '';
		$option['include_file'] = FALSE;
		$option['include_dynamic'] = FALSE;
		$option['include_category'] = FALSE;
		$option['include_search'] = FALSE;
		$option['update_readed'] = FALSE;
		$option['enable_cache'] = FALSE;

		$this->_set($option, $params);

		// 캐쉬 적용
		if( ! empty($option['enable_cache']))
		{
			if(is_array($option['enable_cache']))
			{
				$cache_name = $option['enable_cache'][0];
				$cache_expire = $option['enable_cache'][1];
			}
			else
			{
				$cache_name = $option['enable_cache'];
				$cache_expire = 9999999;
			}
		}

		if(empty($option['enable_cache']) OR ! empty($option['include_search']) OR ($data = $this->cache->get($cache_name)) === FALSE)
		{
			$table = explode(' ', $option['from']);
			$table_prefix = array_pop($table);

			// select
			if( ! is_array($option['select']))
			{
				$option['select'] = array($option['select']);
			}
			foreach($option['select'] as $value)
			{
				$escape = TRUE;
				if(is_array($value))
				{
					$escape = $value[1];
					$value = $value[0];
				}
				$select_array = explode(',', $value);
				foreach($select_array as $field)
				{
					if($escape != FALSE)
					{
						$field = $this->_add_table_prefix($field, $table_prefix);
					}
					$this->db->select($field, $escape);
				}
			}

			// from
			$this->db->from($option['from']);

			// join
			if( ! empty($option['join']))
			{
				if( ! is_array($option['join'][0]))
				{
					$option['join'] = array($option['join']);
				}
				foreach($option['join'] as $item)
				{
					$this->db->join($item[0], $item[1], (isset($item[2])) ? $item[2] : NULL);
				}
			}

			if(is_array($option['include_category']))
			{
				foreach($option['include_category'] as $field => $target)
				{
					$this->db->select(sprintf('%s.title as %s_title', $target, $field));
					$this->db->join(sprintf('global_category %s', $target), sprintf('%s.%s=%s.id', $table_prefix, $field, $target), 'left');
				}
			}

			if($option['update_readed'] == TRUE)
			{
				$this->db->select('readedtable.readed as isreaded, readedtable.is_highlight as ishighlight');
				$this->db->join('readed readedtable', sprintf('%s.id=readedtable.pid AND readedtable.member_id=\'%s\'', $table_prefix, $this->account->get('id')), 'left');
			}

			// searach
			if($option['include_search'] == TRUE)
			{
				$search_field = decode_url($this->link->get_segment('search_field', FALSE));
				$search_keyword = decode_url($this->link->get_segment('search_keyword', FALSE));
				$_field = explode('+', $search_field);

				$conditions = array();
				$search_query = array();
				if( ! empty($search_keyword) AND ! empty($_field))
				{
					foreach($_field as $field)
					{
						$conditions[] = sprintf('%s LIKE "%%%s%%"', $field, $this->db->escape_like_str($search_keyword));
					}
					$search_query = array(sprintf('(%s)', implode(' OR ', $conditions)));

					if( ! empty($option['conditions']['where']))
					{
						$option['conditions']['where'] = array_merge($option['conditions']['where'] , array($search_query));
					}
					else
					{
						$option['conditions']['where'] = $search_query;
					}
				}
			}

			// conditions
			if( ! empty($option['conditions']))
			{
				$this->_set_conditions($option['conditions'], $table_prefix);
			}

			if( ! empty($option['group']))
			{
				if(is_array($option['group']))
				{
					foreach($option['group'] as $options)
					{
						$this->db->group_by($options);
					}
				}
				else
				{
					$this->db->group_by($option['group']);
				}
			}

			$query = $this->db->get();
			$data[$option['return']] = $query->row_array();

			$data['POST'] = $data[$option['return']];

			// for groupware
			if(is_array($option['include_category']))
			{
				foreach($option['include_category'] as $field => $target)
				{
					$category_cache_name = sprintf('_category_%s', $target);
					$category = array(
						'from' => 'global_category',
						'conditions' => array('where' => array('type' => $target)),
						'order' => array('sort' => 'ASC'),
						'enable_cache' => $category_cache_name
					);
					$data[$target] = $this->get_entries($category);
				}
			}

			if(( ! empty($option['hasmany']) AND is_array($option['hasmany'])) OR $option['include_file'] != FALSE OR $option['include_dynamic'] != FALSE)
			{
				$hasmany_array = array();
				if( ! empty($option['hasmany']) && ! isset($option['hasmany'][0]['pkey']))
				{
					$hasmany_array = array($option['hasmany']);
				}
				else
				{
					$hasmany_array = $option['hasmany'];
				}

				if($option['include_file'] != FALSE)
				{
					$hasmany_array[] = array(
						'pkey' => 'id',
						'match' => 'pid',
						'from' => 'files',
						'conditions' => (strtoupper($option['include_file']) != 'ALL') ? array('where' => array('use' => 'Y')) : array(),
						'order' => array('id' => 'ASC'),
						'callback' => '_process_file_entries',
						'return' => 'files'
					);
				}

				if($option['include_dynamic'] != FALSE)
				{
					$hasmany_array[] = array(
						'pkey' => 'id',
						'match' => 'pid',
						'from' => 'variable',
						'order' => array('sort' => 'ASC'),
						'return' => 'dynamic'
					);
				}

				foreach($hasmany_array as $item)
				{
					$pkey = (isset($item['pkey'])) ? $item['pkey'] : 'id';
					$match = (isset($item['match'])) ? $item['match'] : 'pid';
					$return = (isset($item['return'])) ? $item['return'] : 'subdata';

					$primary_key = array();
					if(isset($data[$option['return']][$pkey]))
					{
						$primary_key = $data[$option['return']][$pkey];
					}

					if( ! empty($primary_key))
					{
						$item['conditions'] = array_merge_recursive(array('where' => array($match => $primary_key)), ( ! empty($item['conditions'])) ? $item['conditions'] : array());
						$item['return'] = $return;
						$hasmany = $this->get_entries($item, $option);
						foreach($hasmany[$return] as $_row)
						{
							$data[$return][$_row[$match]][] = $_row;

							if( ! empty($data['dynamic'][$_row[$match]]))
							{
								foreach($data['dynamic'][$_row[$match]] as $item)
								{
									$data[sprintf('dynamicid_%s', $item['variable'])][$item['sort']] = $item['id'];
									$data[sprintf('dynamic_%s', $item['variable'])][$item['sort']] = $item['value'];
								}
							}
						}
					}
				}
			}

			if( ! empty($option['not_exists']) AND ! count($data[$option['return']]))
			{
				if(is_callable(array($option['callback_object'], $option['not_exists'])))
				{
					call_user_func(array($option['callback_object'], $option['not_exists']));
				}
				elseif(is_string($option['not_exists']))
				{
					script('back', $option['not_exists']);
				}
				return FALSE;
			}

			if( ! empty($option['callback']) AND is_callable(array($option['callback_object'], $option['callback'])))
			{
				call_user_func_array(array($option['callback_object'], $option['callback']), array(&$data[$option['return']], &$data));
			}

			if( ! empty($option['enable_cache']))
			{
				$this->cache->save($cache_name, $data, $cache_expire);
			}
		}

		if($option['update_readed'] == TRUE)
		{
			$model = array(
				'select' => 'id, readed',
				'from' => 'readed',
				'conditions' => array('where' => array('pid' => $data[$option['return']]['id'], 'member_id' => $this->account->get('id')))
			);
			$check_readed = $this->get_row($model);
			if(empty($check_readed['data']['id']))
			{
				$model = array(
					'from' => 'readed',
					'data' => array('pid' => $data[$option['return']]['id'], 'member_id' => $this->account->get('id'), 'readed' => date('Y-m-d H:i:s'))
				);
				$this->save_row($model);
			}
			elseif(empty($check_readed['data']['readed']))
			{
				$model = array(
					'from' => 'readed',
					'conditions' => array('where' => array('pid' => $data[$option['return']]['id'], 'member_id' => $this->account->get('id'))),
					'data' => array('readed' => date('Y-m-d H:i:s'))
				);
				$this->save_row($model);
			}
		}

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function get_query($sql)
	{
		$query = $this->db->query($sql);
		$data = $query->result_array();

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function save_row($params)
	{
		$option['data'] = array();
		$option['set'] = array();
		$option['from'] = '';
		$option['conditions'] = array();
		$option['include_seq'] = TRUE;
		$option['include_time'] = TRUE;
		$option['include_file'] = FALSE;
		$option['include_dynamic'] = FALSE;
		$option['update_cache'] = FALSE;

		$this->_set($option, $params);

		$this->load->helper('date');
		$now = mdate('%Y-%m-%d %H:%i:%s');

		$sequence = $this->session->userdata('sequence');

		if( ! empty($option['set']))
		{
			foreach($option['set'] as $key=>$value)
			{
				$this->db->set($key, $value, FALSE);
			}
		}

		if( ! empty($option['conditions']) AND is_array($option['conditions']))
		{
			$data = $this->get_entries($option, $option);
			if(count($data['data']) == 1)
			{
				$data['data'] = array_shift($data['data']);
			}

			if($option['include_time'] == TRUE)
			{
				$option['data']['modified'] = $now;
			}
			$this->_set_conditions($option['conditions']);
			$this->db->update($option['from'], $option['data']);
			$affect_id = (isset($data['data']['id'])) ? $data['data']['id'] : TRUE;
		}
		else
		{
			if($option['include_file'] == TRUE AND ! empty($sequence))
			{
				$option['data']['id'] = $sequence;
			}
			else if($option['include_seq'] != FALSE)
			{
				$option['data']['id'] = $this->_get_sequence();
			}
			if($option['include_time'] == TRUE)
			{
				$option['data']['created'] = $now;
				$option['data']['modified'] = $now;
			}
			$this->db->insert($option['from'], $option['data']);
			$affect_id = $option['data']['id'];
		}

		if($option['include_file'] == TRUE AND ! empty($sequence))
		{
			$this->session->unset_userdata('sequence');

			$model = array(
				'from' => 'files',
				'conditions' => array('where' => array('pid' => $sequence)),
				'data' => array('use' => 'Y')
			);
			$this->save_row($model);
		}

		if($option['include_dynamic'])
		{
			$model = array(
				'from' => 'variable',
				'conditions' => array('where' => array('pid' => $affect_id)),
				'data' => array('delete_prepare' => 'Y')
			);
			$this->save_row($model);

			foreach($_POST as $key => $value)
			{
				if(stripos($key, 'dynamic_') === 0)
				{
					foreach($value as $sort => $item)
					{
						$variable = str_replace('dynamic_', '', $key);
						$dynamic = array(
							'pid' => $affect_id,
							'variable' => $variable,
							'value' => $item,
							'sort' => $sort,
							'delete_prepare' => 'N'
						);

						$id = $_POST[sprintf('dynamicid_%s', $variable)][$sort];
						$model = array(
							'from' => 'variable',
							'conditions' => ( ! empty($id)) ? array('where'=>array('id'=>$id)) : NULL,
							'data' => $dynamic
						);
						$this->save_row($model);
					}
				}
			}

			$model = array(
				'from' => 'variable',
				'conditions' => array('where' => array('pid' => $affect_id, 'delete_prepare' => 'Y'))
			);
			$this->delete_row($model);
		}

		if( ! empty($option['update_cache']))
		{
			// 캐쉬 삭제
			$this->cache->delete($option['update_cache']);
		}

		return $affect_id;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function check_exists($params)
	{
		$option['select'] = '*';
		$option['from'] = '';
		$option['join'] = '';
		$option['conditions'] = array();
		$option['return'] = 'data';

		$this->_set($option, $params);

		$data = $this->get_entries($option);
		if( ! empty($data[$option['return']]))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
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
	protected function _get_sequence()
	{
		$this->db->query(sprintf('INSERT INTO %1$ssequence (seq) VALUES (\'0\')', $this->db->dbprefix));
		$sequence = $this->db->insert_id();

		if($sequence % 10000 == 0)
		{
			$model = array(
				'from' => 'sequence',
				'conditions' => array('where' => array('seq <' => $sequence))
			);
			$this->delete_row($model);
		}

		return $sequence;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function delete_row($params)
	{
		$option['from'] = '';
		$option['conditions'] = array();
		$option['include_file'] = TRUE;
		$option['include_dynamic'] = TRUE;
		$option['update_cache'] = FALSE;

		$this->_set($option, $params);

		$model = array(
			'from' => $option['from'],
			'conditions' => $option['conditions']
		);
		$data = $this->get_entries($model);

		foreach($data['data'] as $item)
		{
			if($option['include_file'] == TRUE)
			{
				$model = array(
					'from' => 'files',
					'conditions' => array('where' => array('pid' => $item['id'])),
					'include_file' => FALSE,
					'include_dynamic' => FALSE
				);
				$files = $this->get_entries($model);
				foreach($files['data'] as $file)
				{
					@unlink(sprintf('%s%s%s', $file['upload_path'], $file['raw_name'], $file['file_ext']));
				}
				$this->delete_row($model);
			}

			if($option['include_dynamic'] == TRUE)
			{
				$model = array(
					'from' => 'variable',
					'conditions' => array('where' => array('pid' => $item['id'])),
					'include_file' => FALSE,
					'include_dynamic' => FALSE
				);
				$this->delete_row($model);
			}
		}

		$this->_set_conditions($option['conditions']);
		$this->db->delete($option['from']);

		if( ! empty($option['update_cache']))
		{
			// 캐쉬 삭제
			$this->cache->delete($option['update_cache']);
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
	public function set_default($table)
	{
		$option['from'] = '';
		$option['return'] = 'data';
		$option['include_category'] = FALSE;

		$params = array();
		if(is_string($table))
		{
			$params['from'] = array($table);
		}
		elseif(is_array($table) AND ! isset($table['from']))
		{
			$params['from'] = $table;
		}
		else
		{
			if( ! is_array($table['from']))
			{
				$params['from'] = array($table['from']);
			}
			else
			{
				$params['from'] = $table['from'];
			}
			if(isset($table['include_category']))
			{
				$params['include_category'] = $table['include_category'];
			}
			if(isset($table['return']))
			{
				$params['return'] = $table['return'];
			}
		}

		$this->_set($option, $params);

		$data = array();
		foreach($params['from'] as $item)
		{
			$cache_name = sprintf('_field_%s', $item);
			if( ! $fields = $this->cache->get($cache_name))
			{
				$fields = $this->db->list_fields($this->db->_protect_identifiers($item, TRUE, FALSE, FALSE));
				$this->cache->save($cache_name, $fields, 600); // 10 minute
			}
			foreach($fields as $field)
			{
				$data[$option['return']][$field] = '';
				$data['POST'][$field] = '';
			}
		}

		if(is_array($option['include_category']))
		{
			foreach($option['include_category'] as $field => $target)
			{
				// 카테고리는 자동 캐쉬
				$cache_name = sprintf('_category_%s', $target);
				if( ! $data[$target] = $this->cache->get($cache_name))
				{
					$category = array(
						'from' => 'global_category',
						'conditions' => array('where' => array('type' => $target)),
						'order' => array('sort' => 'ASC')
					);
					$data[$target] = $this->get_entries($category);
					$this->cache->save($cache_name, $data[$target], 9999999);
				}
			}
		}

		// 임시 첨부파일 다시 불러오기
		if($sequence = $this->session->userdata('sequence'))
		{
			$model = array(
				'from' => 'files',
				'conditions' => array('where' => array('pid' => $sequence)),
				'return' => 'files'
			);
			$files = $this->get_entries($model);

			// 한 페이지 업로드가 여러개 일경우 사용하기 위해
			foreach($files['files'] as $item)
			{
				$files[$item['target']][] = $item;
			}

			$data = array_merge($data, $files);
		}

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function convert()
	{
		$url = array();
		$post_data = $_POST;
		foreach($post_data as $key=>$item)
		{
			if(is_array($item))
			{
				$k = 0;
				foreach($item as $detail)
				{
					if( ! empty($detail))
					{
						$url[sprintf('%s[%s]', $key, $k)] = ($this->input->post('encoding') == 'false') ? $detail : encode_url($detail);
						$k++;
					}
				}
			}
			else
			{
				if ( ! empty($item))
				{
					$url[$key] = ($this->input->post('encoding') == 'false') ? $item : encode_url($item);
				}
			}
		}

		$segments = array();
		$redirect = $this->input->post('redirect');
		if(empty($redirect))
		{
			$referer = $this->input->server('HTTP_REFERER');
			$url_default = explode('/', str_replace(site_url(), '', $referer));

			$this->link->reset();
			if($this->link->base['map'] == $url_default[0])
			{
				$action = $url_default[3];
				$exists_segment = array_slice($url_default, 4);
			}
			else
			{
				$action = $url_default[2];
				$exists_segment = array_slice($url_default, 3);
			}
			$referer_url = str_replace(sprintf('/%s', implode('/', $exists_segment)), '', $referer);

			$key = array();
			$value= array();
			foreach($exists_segment as $n=>$item)
			{
				if($n%2 == 0)
				{
					$key[] = $item;
				}
				else
				{
					$value[] = $item;
				}
			}

			$segments = array_combine($key, $value);
		}

		$url = array_merge($segments, $url);

		unset($url['redirect']);
		unset($url['encoding']);
		unset($url['page']);
		unset($url['button_x']);
		unset($url['button_y']);
		unset($url['x']);
		unset($url['y']);

		$segment = $this->uri->assoc_to_uri($url);

		if(empty($redirect))
		{
			redirect(sprintf('%s/%s', $referer_url, $segment));
		}
		else
		{
			redirect(sprintf('%s/%s', $redirect, $segment));
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
	public function _comment($params)
	{
		$option['id'] = '';
		$option['pid'] = '';
		$option['includeForm'] = TRUE;
		$option['updateReaded'] = TRUE;

		$this->_set($option, $params);

		$this->load->library('image_lib');

		$model = array(
			'from' => 'comment',
			'conditions' => array('where' => array('pid' => $option['pid'])),
			'hasmany' => array(
				'pkey' => 'ismember',
				'match' => 'pid',
				'from' => 'files',
				'conditions' => array('where' => array('use' => 'Y')),
				'return' => 'files'
			),
			'order' => array('fid'=>'ASC', 'thread'=>'ASC'),
			'group' => array('id'),
			'include_file' => TRUE,
			'include_readed' => TRUE,
			'update_readed' => $option['updateReaded'],
			'callback' => '_process_comment_entries'
		);
		$option['entries'] = $this->get_entries($model);

		$view = array(
			'skin' => 'comment/index',
			'data' => $option,
			'return' => TRUE
		);
		$listing = $this->show($view);

		$form = '';
		if($option['includeForm'] == TRUE AND $this->auth->check(array('action'=>'comment')) == TRUE)
		{
			$option['files'] = array();
			if($pid = $this->session->userdata('sequence'))
			{
				$model = array(
					'from' => 'files',
					'conditions' => array('where' => array('pid' => $pid)),
					'order' => array('id' => 'ASC'),
					'callback' => '_process_file_entries'
				);
				$files = $this->get_entries($model);
				$option['files'] = $files['data'];
			}

			$view = array(
				'skin' => 'comment/form',
				'data' => $option,
				'return' => TRUE
			);
			$form = $this->show($view);
		}

		return $form.$listing;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function comment_write()
	{
		$id = $this->link->get_segment('cid', FALSE);
		$pid = $this->link->get_segment('pid', FALSE);
		$tid = $this->link->get_segment('tid', FALSE);

		if( ! empty($id))
		{
			$this->title = '댓글 수정';

			$model = array(
				'from' => 'comment',
				'conditions' => array('where' => array('id' => $id)),
				'include_file' => 'ALL',
				'not_exists' => '존재하지 않는 댓글 입니다.'
			);
			$data = $this->get_row($model);
			$data['data']['tid'] = '';
		}
		else
		{
			$this->title = '답글';

			$data = $this->set_default('comment');
			$data['data']['pid'] = $pid;
			$data['data']['tid'] = $tid;
		}

		$view = array(
			'skin' => 'comment/popup/write',
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
	public function comment_save()
	{
		$this->form_validation->set_rules('pid', 'pid', 'trim|required|numeric');
		$this->form_validation->set_rules('redirect', 'redirect', 'trim|required');
		$this->form_validation->set_rules('contents', '내용', 'trim|required');

		if ($this->form_validation->run() === FALSE)
		{
			$this->form_validation->set_error_delimiters('<div>', '</div>');
			message(validation_errors(), 'error');
			redirect(decode_url($this->input->post('redirect')));
		}

		$id = $this->input->post('id');
		$tid = $this->input->post('tid');

		$data['pid'] = $this->input->post('pid');
		$data['contents'] = $this->input->post('contents');
		$data['contents'] = preg_replace(sprintf("|src=[\"']?%s(.*?)[\"']|i", rtrim(site_url(), '/')), "src=\"$1\"", $data['contents']);
		$data['contents'] = preg_replace(sprintf("|href=[\"']?%s(.*?)[\"']|i", rtrim(site_url(), '/')), "href=\"$1\"", $data['contents']);

		if(empty($id))
		{
			$data['ismember'] = $this->account->get('id');
			$data['name'] = $this->account->get('name');

			if(empty($tid))
			{
				$model = array(
					'select' => 'MIN(`fid`) as fid',
					'from' => 'comment'
				);
				$row = $this->get_row($model);

				$fid = ($row['data']['fid'] < 0) ? $row['data']['fid']-1 : -1;

				$data['fid'] = $fid;
				$data['thread'] = 'AA';
			}
			else
			{
				$model = array(
					'select' => 'fid, thread',
					'from' => 'comment',
					'conditions' => array('where' => array('id' => $tid)),
					'not_exists' => '원 댓글이 존재하지 않습니다.'
				);
				$parent = $this->get_row($model);

				$model = array(
					'select' => 'MAX(thread) as thread',
					'from' => 'comment',
					'conditions' => array('where' => array('fid' => $parent['data']['fid']), 'like' => array('thread' => array(sprintf('%s__', $parent['data']['thread']), 'none')))
				);
				$thread = $this->get_row($model);

				if( ! empty($thread['data']['thread']))
				{
					$thread_str = $thread['data']['thread'];
					$thread_str++;
				}
				else
				{
					$thread_str = sprintf('%sAA', $parent['data']['thread']);
				}

				$data['fid'] = $parent['data']['fid'];
				$data['thread'] = $thread_str;
			}

			$data['ip'] = $this->input->ip_address();
		}

		$model = array(
			'from' => 'comment',
			'conditions' => ( ! empty($id)) ? array('where' => array('id' => $id)) : NULL,
			'data' => $data,
			'update_readed' => array(TRUE, $data['pid']),
			'include_file' => TRUE
		);
		$affect_id = $this->save_row($model);

		$url = decode_url($this->input->post('redirect')).'#comment-'.$affect_id;

		// 게시판 댓글수 및 "NEW" 업데이트
		$model = array(
			'from' => 'bbs',
			'conditions' => array('where' => array('id' => $data['pid']))
		);
		if($this->check_exists($model) == TRUE)
		{
			$model = array(
				'select' => 'COUNT(id) as total_comment',
				'from' => 'comment',
				'conditions' => array('where' => array('pid' => $data['pid']))
			);
			$row = $this->get_row($model);

			$model = array(
				'from' => 'bbs',
				'conditions' => array('where' => array('id' => $data['pid'])),
				'data' => array('total_comment' => $row['data']['total_comment'], 'isnew' => 'Y')
			);
			$this->save_row($model);
		}

		message('저장 되었습니다.');

		if(empty($tid))
		{
			redirect($url);
		}
		else
		{
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
	public function comment_delete()
	{
		$id = $this->link->get_segment('cid');
		$redirect = get_field('redirect');

		$model = array(
			'from' => 'comment',
			'conditions' => array('where' => array('id' => $id)),
			'not_exists' => '존재하지 않는 댓글 입니다.'
		);
		$data = $this->get_row($model);

		$model = array(
			'from' => 'comment',
			'conditions' => array('where' => array('id' => $id))
		);
		$this->delete_row($model);

		// 게시판 댓글수 업데이트
		$model = array(
			'from' => 'bbs',
			'conditions' => array('where' => array('id' => $data['data']['pid']))
		);
		if($this->check_exists($model) == TRUE)
		{
			$model = array(
				'select' => 'COUNT(id) as total_comment',
				'from' => 'comment',
				'conditions' => array('where' => array('pid' => $data['data']['pid']))
			);
			$row = $this->get_row($model);

			$model = array(
				'from' => 'bbs',
				'conditions' => array('where' => array('id' => $data['data']['pid'])),
				'data' => array('total_comment' => $row['data']['total_comment'])
			);
			$this->save_row($model);
		}

		message('삭제 되었습니다.');
		redirect($redirect);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	protected function _process_comment_entries(&$item, $data)
	{
		$item['depth'] = strlen($item['thread'])-2;

		$width = 100;
		$height = 100;

		$item['thumb'] = site_url($this->image_lib->noimg($width, $height));

		if( ! empty($data['files'][$item['ismember']]))
		{
			foreach($data['files'][$item['ismember']] as $file)
			{
				if($file['target'] == 'member_picture')
				{
					$config = array(
						'source_image' => $file['upload_path'].$file['raw_name'].$file['file_ext'],
						'new_image' => sprintf('%sthumbs/%s_%s_%s%s', $file['upload_path'], $file['raw_name'], $width, $height, $file['file_ext']),
						'width' => $width,
						'height' => $height,
						'maintain_ratio' => FALSE
					);
					$item['thumb'] = site_url($this->image_lib->thumb($config));
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
	public function grant()
	{
		$id = $this->link->get_segment('menu_id', FALSE);
		if(empty($id))
		{
			$id = $this->menu->current['id'];
		}

		$model = array(
			'from' => 'menu_item',
			'conditions' => array('where' => array('id' => $id)),
			'not_exists' => '_error_exists',
			'include_company' => FALSE
		);
		$data = $this->get_row($model);

		$this->title = sprintf('%s 권한 관리', $data['data']['title']);

		list($module) = explode('/', $data['data']['target']);

		// 캐쉬 삭제는 직접해야 합니다.
		$cache_name = sprintf('module_config_%s', $module);
		if(($arr = $this->cache->get($cache_name)) === FALSE)
		{
			$file = sprintf('./modules/%s/conf/module.xml', $module);
			if(file_exists($file))
			{
				$conf = simplexml_load_file($file);
				$arr = object2array($conf);
				$this->cache->save($cache_name, $arr, 9999999);
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
					if($section['target'] == $data['data']['target'])
					{
						if(isset($section['grants']['grant']))
						{
							$data['grant_option'] = $section['grants']['grant'];

							if( ! isset($data['grant_option'][0]))
							{
								$data['grant_option'] = array($data['grant_option']);
							}
						}
					}
				}
			}
		}

		foreach($data['grant_option'] as $i=>$item)
		{
			if(isset($item['@attributes']['setup']) AND $item['@attributes']['setup'] == 'N')
			{
				unset($data['grant_option'][$i]);
				continue;
			}
		}

		if(empty($data['grant_option']))
		{
			script('close', '해당 페이지는 권한설정을 지원하지 않습니다.');
		}

		// 분산관리
		$grant_all = array(
			'@attributes' => array(
				'title' => '관리권한',
				'action' =>'__ALL__'
			)
		);
		array_push($data['grant_option'], $grant_all);

		$model = array(
			'from' => 'member_group',
			'order' => array('sort' => 'ASC'),
			'include_company' => FALSE
		);
		$data['group'] = $this->get_entries($model);
		array_push($data['group']['data'], array('title'=>'모든사용자', 'id'=>'__ALL__', 'admin'=>'N'));

		$model = array(
			'from' => 'permission',
			//'conditions' => array('where' => array('bridge_id' => $this->bridge->get('id'), 'menu_id' => $id)),
			'conditions' => array('where' => array('menu_id' => $id)),
			'include_company' => FALSE
		);
		$permissions = $this->get_entries($model);

		$data['permission'] = array();
		if( ! empty($permissions['data']))
		{
			foreach($permissions['data'] as $item)
			{
				$data['permission'][$item['action']][$item['group_id']] = TRUE;
			}
		}
		else
		{
			foreach($data['grant_option'] as $item)
			{
				if(isset($item['@attributes']['default']) AND $item['@attributes']['default'] == 'allow')
				{
					foreach($data['group']['data'] as $group)
					{
						$data['permission'][$item['@attributes']['action']][$group['id']] = TRUE;
					}
				}
				else
				{
					if(isset($item['option']))
					{
						foreach($item['option'] as $options)
						{
							if( ! isset($options['@attributes']))
							{
								$options['@attributes'] = $options;
							}

							if(isset($options['@attributes']['uncase']) AND isset($options['@attributes']['default']) AND $options['@attributes']['default'] == 'allow')
							{
								foreach($data['group']['data'] as $group)
								{
									$data['permission'][$item['@attributes']['action']][$group['id']] = TRUE;
								}
								break;
							}
						}
					}
				}
			}
		}

		$model = array(
			'select' => 'A.id, A.userid, A.name',
			'from' => 'member A',
			//'conditions' => array('where' => array('bridge_id' => $this->bridge->get('id'))),
			'order' => array('A.name' => 'ASC')
		);
		$data['member'] = $this->get_entries($model);

		$this->html['css'][] = '/_res/libs/chosen/chosen.min.css';
		$this->html['js'][] = '/_res/libs/chosen/chosen.jquery.js';

		$view = array(
			'skin' => 'menu/popup/grant',
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
	public function save_grant()
	{
		$this->form_validation->set_rules('id', '맵 아이디', 'required|numeric');
		if ($this->form_validation->run() === FALSE)
		{
			$this->grant();
			return FALSE;
		}

		$data['menu_id'] = $this->input->post('id');
		$data['delete_prepare'] = 'N';
		$group = $this->input->post('group');
		$busu = $this->input->post('busu');
		$member = $this->input->post('member');

		$model = array(
			'from' => 'permission',
			//'conditions' => array('where' => array('bridge_id' => $this->bridge->get('id'), 'menu_id' => $data['menu_id'])),
			'conditions' => array('where' => array('menu_id' => $data['menu_id'])),
			'data' => array('delete_prepare' => 'Y'),
			'include_company' => FALSE
		);
		$this->save_row($model);

		//$data['bridge_id'] = $this->bridge->get('id');

		// 그룹별
		foreach($group as $action => $value)
		{
			$data['type'] = 'group';
			$data['action'] = str_replace(array('/', ':'), array('.', '|'), $action);

			foreach($value as $group_id)
			{
				$data['group_id'] = $group_id;
				$model = array(
					'from' => 'permission',
					'data' => $data,
					'include_company' => FALSE
				);
				$this->save_row($model);
			}
		}

		// 회원별
		foreach($member as $action => $value)
		{
			$data['type'] = 'member';
			$data['action'] = str_replace(array('/', ':'), array('.', '|'), $action);

			foreach($value as $group_id)
			{
				$data['group_id'] = $group_id;
				$model = array(
					'from' => 'permission',
					'data' => $data,
					'include_company' => FALSE
				);
				$this->save_row($model);
			}
		}

		$model = array(
			'from' => 'permission',
			//'conditions' => array('where' => array('bridge_id' => $this->bridge->get('id'), 'menu_id' => $data['menu_id'], 'delete_prepare' => 'Y')),
			'conditions' => array('where' => array('menu_id' => $data['menu_id'], 'delete_prepare' => 'Y')),
			'include_company' => FALSE
		);
		$this->delete_row($model);

		// 캐쉬 삭제
		//$this->cache->delete(sprintf('permission_%s_%s', $this->bridge->get('id'), $data['menu_id']));
		$this->cache->delete(sprintf('permission_%s', $data['menu_id']));
		//$this->cache->delete(sprintf('permission_admin_%s_%s', $this->bridge->get('id'), $data['menu_id']));
		$this->cache->delete(sprintf('permission_admin_%s', $data['menu_id']));

		message('저장 되었습니다.');
		redirect($this->link->get(array('action'=>'grant')));
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function _request_password($params)
	{
		$option['select'] = '';
		$option['from'] = '';
		$option['conditions'] = '';
		$option['layout'] = NULL;

		$this->_set($option, $params);

		if($this->auth->admin() == TRUE)
		{
		 return TRUE;
		}

		if($this->session->userdata('_checked_passwd') == array($option['select'], $option['from'], $option['conditions']))
		{
			return TRUE;
		}

		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$this->form_validation->set_rules('_request_passwd', '비밀번호', 'trim|required');
			if($this->form_validation->run() === TRUE)
			{
				$row = $this->get_row($option);
				if($this->encrypt->decode($row['data'][$option['select']]) == $this->input->post('_request_passwd'))
				{
					$this->session->set_userdata('_checked_passwd', array($option['select'], $option['from'], $option['conditions']));
					return TRUE;
				}
				else
				{
					message('비밀번호가 일치하지 않습니다.', 'error');
				}

			}
		}

		$this->load->_ci_view_path = 'empty';

		$view = array(
			'skin' => 'common/request_password',
			'layout' => $option['layout']
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
	public function _editor($params)
	{
		static $sequence = 0;
		$sequence++;

		$option['input'] = 'contents';
		$option['pid'] = '';
		$option['sequence'] = $sequence;
		$option['editor_path'] = sprintf('/%s/modules/daumeditor/', SITE);
		$option['skin'] = 'default';

		$this->_set($option, $params);

		$this->html['css'][] = './modules/daumeditor/css/editor.css?v=20160510.2';

		$this->html['js'][] = array('./modules/daumeditor/js/editor_loader.js?v=20140212', FALSE);
		$this->html['js'][] = array('./modules/daumeditor/js/emoticon.js?v=20160510', FALSE);

		switch($option['skin'])
		{
			case 'mini' :
				$skin = 'daumeditor/mini';
				break;
			case 'tiny' :
				$skin = 'daumeditor/tiny';
				break;
			default :
				$skin = 'daumeditor/editor';
		}

		$view = array(
			'skin' => $skin,
			'data' => $option,
			'return' => TRUE
		);
		return $this->show($view);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function _uploader($params=array())
	{
		static $sequence = 0;
		$sequence++;

		$this->load->config('attachment/attachment');

		$option['pid'] = '';
		$option['target'] = '';
		$option['limit'] = 0;
		$option['skin'] = 'normal';
		$option['target_button'] = '';
		$option['files'] = array();
		$option['sequence'] = $sequence;
		$option['editor_path'] = sprintf('/%s/modules/daumeditor/', SITE);
		$option['editor_sequence'] = '';
		$option['edit'] = TRUE;

		$config_allow_ext = config_item('attachment_allow_ext');
		$allow_ext = array();
		foreach($config_allow_ext as $exts)
		{
			$allow_ext[] = implode(',', $exts);
		}

		$option['ext_desc'] = '사용자 지정 파일';
		$option['ext'] = implode(',', $allow_ext);

		$option['image_ext'] = $config_allow_ext['이미지'];
		$option['media_ext'] = $config_allow_ext['미디어'];

		$this->_set($option, $params);

		$timestamp = time();
		$option['timestamp'] = $timestamp;
		$option['token'] = $this->encrypt->encode($timestamp);
		$option['max_upload_size'] = ini_get('upload_max_filesize');

		$option['image'] = array();
		$option['media'] = array();
		$option['file'] = array();

		if( ! empty($option['files']))
		{
			if(isset($option['files'][$option['pid']][0]['id']))
			{
				$option['files'] = $option['files'][$option['pid']];
			}

			foreach($option['files'] as $item)
			{
				$ext = str_replace('.', '', strtolower($item['file_ext']));
				if(in_array($ext, $option['image_ext']))
				{
					$option['image'][] = $item;
				}
				elseif(in_array($ext, $option['media_ext']))
				{
					$option['media'][] = $item;
				}
				else
				{
					$option['file'][] = $item;
				}
			}
		}

		$this->html['css'][] = './modules/attachment/css/attachment.css?v=20130206';
		$this->html['css'][] = './modules/attachment/js/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css';
		$this->html['js'][] = array('./modules/attachment/js/plupload/js/moxie.js?v=20140212', FALSE);
		$this->html['js'][] = array('./modules/attachment/js/plupload/js/plupload.dev.js?v=20140212', FALSE);
		$this->html['js'][] = array('./modules/attachment/js/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js?v=20140212', FALSE);
		$this->html['js'][] = array('./modules/attachment/js/plupload/js/i18n/ko.js?v=20140212', FALSE);

		$view = array(
			'skin' => sprintf('attachment/%s', $option['skin']),
			'data' => $option,
			'return' => TRUE
		);
		return $this->show($view);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function _message_link()
	{
		return $this->link->get(array('module'=>'message', 'controller'=>'box', 'action'=>'index'), TRUE);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function _zipcode_link($params=array(), $reault=FALSE)
	{
		$option['zip'] = 'input[name="zipcode"]';
		$option['address1'] = 'input[name="address1"]';
		$option['address2'] = 'input[name="address2"]';
		$option['request'] = $reault;

		$this->_set($option, $params);

		return $this->link->get(array('module'=>'zipcode', 'controller'=>'search', 'action'=>'index', 'zip'=>encode_url($option['zip']), 'address1'=>encode_url($option['address1']), 'address2'=>encode_url($option['address2']), 'request'=>encode_url($option['request'])), TRUE);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function js()
	{
		$this->output->enable_profiler(FALSE);
		$this->output->set_header('Content-type: application/javascript');

		$scriptdata = $this->session->userdata('scriptdata');
		$this->session->unset_userdata('scriptdata');

		$scriptBuffer = '';
		$view_file = array();
		$view_data = decode_url($scriptdata);

		if( ! empty($view_data))
		{
			foreach($view_data as $view)
			{
				$view_file[] = $view[0];
				$scriptBuffer .= $this->load->view(sprintf('../../%s', $view[0]), $view[1], TRUE);
			}
		}

		$scriptBuffer = preg_replace('|<script(.*)>|i', '', $scriptBuffer);
		$scriptBuffer = preg_replace('|</(.*)script>|i', '', $scriptBuffer);

		echo $scriptBuffer;

		$this->load->_ci_view_path = NULL;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	private function _process_file_entries(&$item)
	{
		$item['ext'] = strtolower(str_replace('.', '', $item['file_ext']));
		$item['ext_icon'] = ext($item['file_ext']);
		$item['download_link'] = $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'down', 'id'=>$item['id']), TRUE);
		$item['viewer_link'] = $this->link->get(array('module'=>'attachment', 'controller'=>'files', 'action'=>'viewer', 'id'=>$item['id'].$item['file_ext']), TRUE);

		$item['media_link'] = '';
		if(in_array($item['file_ext'], array('.mp3', '.m4a')))
		{
			$item['media_link'] = sprintf('/%s%s%s', $item['upload_path'], $item['raw_name'], $item['file_ext']);
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
	public function _category_link($params)
	{
		$option['type'] = '';
		$option['title'] = '카테고리 관리';

		$this->_set($option, $params);

		return $this->link->get(array('module'=>'category', 'controller'=>'manager', 'action'=>'index', 'type'=>$option['type'], 'title'=>encode_url($option['title'])), TRUE);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
    public function get_holiday($params=array())
    {
        $option['start_date'] = '';
        $option['end_date'] = '';
        $option['enable_cache'] = FALSE;
        $option['return'] = FALSE;

        $this->_set($option, $params);

        if(empty($option['start_date']))
        {
            $option['start_date'] = $this->link->get_segment('start_date');
        }

        if(empty($option['start_date']))
        {
            $option['end_date'] = $this->link->get_segment('end_date');
        }

        if( ! empty($option['enable_cache']))
        {
            if(is_array($option['enable_cache']))
            {
                $cache_name = $option['enable_cache'][0];
                $cache_expire = $option['enable_cache'][1];
            }
            else
            {
                $cache_name = $option['enable_cache'];
                $cache_expire = 86400*30; // 30일동안 보관
            }
        }

        if(empty($option['enable_cache']) OR ($result = $this->cache->get($cache_name)) === FALSE)
        {
            $api_key = 'plani_api_test_3EdGdd52345';
            $url = sprintf('http://api.plani.co.kr/api/calendar/holiday/key/%s/start_date/%s/end_date/%s', $api_key, $option['start_date'], $option['end_date']);
            $result = file_get_contents($url) or message('API 서버에 접속 할 수 없습니다. (H)', 'error');

            if( ! empty($option['enable_cache']))
            {
                $this->cache->save($cache_name, $result, $cache_expire);
            }
        }

        if($option['return'] == FALSE)
        {
            $data = json_decode($result, TRUE);
            echo $data['data'];
        }
        else
        {
            $data = json_decode($result, TRUE);

            $return = array();
            foreach($data['data'] as $item)
            {
                $return[$item['date']] = $item['holiday'];
            }

            return $return;
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
	public function highlight()
	{
		$target = $this->input->post('target');
		$type = $this->input->post('type');

		if(empty($target) OR empty($type))
		{
			script('back', '잘못된 접근 입니다.');
		}

		$model = array(
			'from' => 'readed',
			'conditions' => array('where' => array('pid' => $target, 'member_id' => $this->account->get('id'))),
			'data' => array('is_highlight' => ($type == 'mark') ? 'Y' : 'N')
		);
		$this->save_row($model);

		echo 'true';
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function _get_d($company_id, $prefix)
	{
		$_d = array();
		$tables = $this->db->list_tables();
		foreach($tables as $table)
		{
			if(stripos($table, sprintf('_d_%s', $prefix)) > 0)
			{
				$table = str_replace($this->db->dbprefix, '', $table);

				$_d_model = array(
					'from' => $table,
					'conditions' => array('where' => array('company_id' => $company_id)),
					'order' => array('sort' => 'ASC'),
					'include_file' => TRUE,
					'callback' => '_process_d_entries',
				);
				$_d_data = $this->get_entries($_d_model);

				if(empty($_d_data['data']))
				{
					$structure = $this->set_default($table);
					$_d_data['data'] = array($structure['data']);
					$_d_data['data'][0]['fileList'] = '';
				}

				$key = str_replace('_d_', '', $table);
				$_d[$key] = $_d_data['data'];
			}
		}

		return $_d;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function _save_d($company_id)
	{
		$postData = $this->input->post('_d');
		$deleteData = $this->input->post('_d_delete');
		$fileID = '';

		if( ! empty($postData))
		{
			foreach($postData as $table => $item)
			{
				$data = array();
				foreach($item as $column => $values)
				{
					foreach($values as $sort=>$value)
					{
						if(!empty($value)){
							$data[$sort][$column] = trim($value);
						}
					}
				}

				$i = 0;
				foreach($data as $k=>$saveData)
				{
					$id = $saveData['id'];
					unset($saveData['id']);

					$isCheck = FALSE;
					foreach($saveData as $checkKey=>$checkData)
					{
						if($checkKey != 'sort' AND ! empty($checkData))
						{
							$isCheck = TRUE;
							break;
						}
					}

					if($isCheck == FALSE AND empty($_FILES['_d']['name'][$table]['file'][$k]))
					{
						CONTINUE;
					}

					$saveData['company_id'] = $company_id;
					$saveData['ismember'] = $this->account->get('id');
					$saveData['sort'] = $i;

					$model = array(
						'from' => sprintf('_d_%s', $table),
						'conditions' => ( ! empty($id)) ? array('where' => array('company_id' => $company_id, 'id' => $id)) : NULL,
						'data' => $saveData,
						'include_file' => TRUE
					);
					$affect_id = $this->save_row($model);

					if( ! empty($_FILES['_d']['name'][$table]['file'][$k]))
					{
						$config['upload_path'] = sprintf('attach/files/%s/', date('Ymd'));
						if( ! is_dir($config['upload_path']))
						{
							if( ! @mkdir($config['upload_path'], 0777, TRUE))
							{
								$this->_exception('디렉토리를 생성할 수 없습니다. 생성 권한 또는 디스크 용량을 확인해 주세요.');
							}
						}
						$config['encrypt_name'] = TRUE;
						$config['allowed_types'] = '*';

						$this->load->config('attachment/attachment');
						$this->load->library('upload');
						$this->upload->initialize($config);

						$config_allow_ext = config_item('attachment_allow_ext');
						$allow_ext = array();
						foreach($config_allow_ext as $exts)
						{
							$allow_ext = array_merge($allow_ext, $exts);
						}

						$chk_ext = strtolower(substr(strrchr($_FILES['_d']['name'][$table]['file'][$k], '.'), 1));
						if( ! in_array($chk_ext, $allow_ext))
						{
							$this->_exception('허용되지 않은 확장자 입니다.');
						}

						$_FILES['tempfile']['name'] = $_FILES['_d']['name'][$table]['file'][$k];
						$_FILES['tempfile']['type'] = $_FILES['_d']['type'][$table]['file'][$k];
						$_FILES['tempfile']['tmp_name'] = $_FILES['_d']['tmp_name'][$table]['file'][$k];
						$_FILES['tempfile']['error'] = $_FILES['_d']['error'][$table]['file'][$k];
						$_FILES['tempfile']['size'] = $_FILES['_d']['size'][$table]['file'][$k];

						if( ! $this->upload->do_upload('tempfile'))
						{
							$this->_exception($this->upload->display_errors());
						}
						else
						{
							$upload_data = $this->upload->data();

							$model = array(
								'from' => 'files',
								'conditions' => array('where' => array('pid' => $affect_id)),
								'include_file' => FALSE,
								'include_dynamic' => FALSE
							);
							$files = $this->get_entries($model);
							foreach($files['data'] as $file)
							{
								@unlink(sprintf('%s%s%s', $file['upload_path'], $file['raw_name'], $file['file_ext']));
							}
							$this->delete_row($model);

							$data = array(
								'pid' => $affect_id,
								'orig_name' => $upload_data['orig_name'],
								'raw_name' => $upload_data['raw_name'],
								'file_ext' => $upload_data['file_ext'],
								'file_size' => $upload_data['file_size']*1024,
								'upload_path' => $config['upload_path'],
								'use' => 'Y'
							);
							$model = array(
								'from' => 'files',
								'data' => $data
							);
							$fileID = $this->save_row($model);
						}
					}

					$i++;
				}
			}
		}

		if( ! empty($deleteData))
		{
			foreach($deleteData as $table => $item)
			{
				if( ! empty($item['id']))
				{
					$model = array(
						'from' => sprintf('_d_%s', $table),
						'conditions' => array('where' => array('company_id' => $company_id), 'where_in' => array('id' => $item['id']))
					);
					$this->delete_row($model);
				}
			}
		}

		return $fileID;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	protected function _process_d_entries(&$item, $data)
	{
		$item['files'] = array();
		if( ! empty($data['files'][$item['id']]))
		{
			foreach($data['files'][$item['id']] as $file)
			{
				$item['files'][] = $file;
			}
		}

		$view = array(
			'skin' => 'common/attachment',
			'layaout' => '',
			'data' => array('files' => $item['files']),
			'return' => TRUE
		);
		$item['fileList'] = $this->show($view);
	}
    
  // --------------------------------------------------------------------

  /**
   * Initialize the Controller Preferences
   *
   * @access    private
   * @params array
   * @return    mixed
   */
  public function _set_database($active='')
  {
        //return FALSE;

    if( ! empty($active))
    {
      if( ! isset($this->connected_db[$active]))
      {
        $this->connected_db[$active] = $this->load->database($active, TRUE);
      }
      $this->db = $this->connected_db[$active];
      $this->current_active_db = $active;
    }
    else
    {
      $this->db = $this->connected_db['default'];
      $this->current_active_db = 'default';
    }
  }


}
