<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Initialize {

	private $CI;
	private $menu = array();
	private $menus = array();
	private $db = array();
	private $conn_id = FALSE;

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function set_header()
	{
		header('Pragma: no-cache');
		header('Cache-Control: no-cache, must-revalidate');

		/*
		if(isset($_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		{
			header('Content-Encoding: gzip');
			ob_start('ob_gzhandler');
		}
		else
		{
			ob_start();
		}
		*/
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	private function _connect_db()
	{
		switch($this->db['dbdriver'])
		{
			case 'mysql' :
			case 'mysqli' :
				if(isset($this->db['port']))
				{
					$this->db['hostname'] = sprintf('%s:%s', $this->db['hostname'], $this->db['port']);
				}
				$this->conn_id = mysqli_connect($this->db['hostname'], $this->db['username'], $this->db['password'], $this->db['database']) or die('Database Connection Error.');

				$query = mysqli_query($this->conn_id, 'SET NAMES UTF8');
				break;
			case 'postgre' :
				if( ! isset($this->db['port']))
				{
					$this->db['port'] = 5432;
				}
				$conn_string = sprintf('host=%s port=%s dbname=%s user=%s password=%s', $this->db['hostname'], $this->db['port'], $this->db['database'], $this->db['username'], $this->db['password']);
				$this->conn_id = pg_connect($conn_string);
				break;
			default :
				exit('지원하지 않는 DB Driver 입니다.');
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
	private function _get_menu()
	{
		$result = array();
		switch($this->db['dbdriver'])
		{
			case 'mysql' :
			case 'mysqli' :
				$sql = '
				SELECT `A`.*, `B`.`map`, `B`.`isadmin`
				FROM (`'.$this->db['dbprefix'].'menu_item` A)
				JOIN `'.$this->db['dbprefix'].'menu` B ON `A`.`menu_id` = `B`.`id`
				ORDER BY `A`.`sort` ASC
				';
				$query = mysqli_query($this->conn_id, $sql) or die(mysqli_error($this->conn_id));

				while($route = mysqli_fetch_array($query, MYSQLI_ASSOC))
				{
					$result[] = $route;
				}
				break;
			case 'postgre' :
				$sql = '
				SELECT A.*, B.map, B.isadmin
				FROM '.$this->db['dbprefix'].'menu_item A
				JOIN '.$this->db['dbprefix'].'menu B ON A.menu_id = B.id
				ORDER BY A.sort ASC
				';
				$query = pg_query($this->conn_id, $sql) or die(pg_last_error($this->conn_id));

				while($route = pg_fetch_assoc($query))
				{
					$result[] = $route;
				}
				break;
		}

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	private function _get_menu_file($pid)
	{
		$result = array();
		switch($this->db['dbdriver'])
		{
			case 'mysql' :
			case 'mysqli' :
				$sql = '
				SELECT *
				FROM (`'.$this->db['dbprefix'].'files`)
				WHERE `pid` IN (\''.implode('\', \'', $pid).'\') AND `use` = \'Y\'
				ORDER BY `id` ASC
				';
				$query = mysqli_query($this->conn_id, $sql);

				while($file = mysqli_fetch_array($query, MYSQLI_ASSOC))
				{
					$result[$file['pid']]['files'][] = $file;
				}
				break;
			case 'postgre' :
				$sql = '
				SELECT *
				FROM '.$this->db['dbprefix'].'files
				WHERE pid IN (\''.implode('\', \'', $pid).'\') AND use = \'Y\'
				ORDER BY id ASC
				';
				$query = pg_query($this->conn_id, $sql) or die(pg_last_error($this->conn_id));

				while($file = pg_fetch_assoc($query))
				{
					$result[$file['pid']]['files'][] = $file;
				}
				break;
		}

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function set_router()
	{
		global $DB_ROUTES;

		$map_file = FCPATH.'attach/config/map.php';
		$map_string = $this->_read_file($map_file);

		$map_list = explode("\n", $map_string);
		array_shift($map_list);
		$map_list = array_map('trim', $map_list);

		require_once APPPATH.'config/database.php';

		$this->db = $db[$active_group];
		$this->_connect_db();

		$menus = array();
		$menus_recursive = array();
		$pid = array();

		$routes = $this->_get_menu();
		foreach($routes as $route)
		{
			$menus[] = $route;
			$menus_recursive[$route['parent_id']][] = $route;
			$pid[] = $route['id'];
		}

		$files = $this->_get_menu_file($pid);

		$maps = array();
		$routes = array();
		foreach($menus as $route)
		{
			if( ! empty($route['url']) AND ! empty($route['target']))
			{
				$prefix1 = sprintf('%s%s', ( ! empty($route['map'])) ? sprintf('%s/', $route['map']) : '', $route['url']);
				$routes[$prefix1] = sprintf('%s%s', $route['target'], ( ! empty($route['param'])) ? sprintf('/%s', $route['param']) : '');

				$prefix2 = sprintf('%s%s/(:any)', ( ! empty($route['map'])) ? sprintf('%s/', $route['map']) : '', $route['url']);
				$explode_target = explode('/', $route['target']);
				$routes[$prefix2] = sprintf('%s/$1', array_shift($explode_target));
			}

			if($route['index'] == 'Y')
			{
				if( ! empty($route['map']))
				{
					$routes[sprintf('%s', $route['map'])] = sprintf('%s%s', $route['target'], ( ! empty($route['param'])) ? sprintf('/%s', $route['param']) : '');
				}
				else
				{
					$routes['default_controller'] = sprintf('%s%s', $route['target'], ( ! empty($route['param'])) ? sprintf('/%s', $route['param']) : '');
				}
			}

			if( ! empty($route['map']))
			{
				$maps[] = $route['map'];
			}

			if(empty($route['url']))
			{
				$child_url = $this->_find_child_url($menus_recursive, $route);
				if( ! empty($child_url))
				{
					if(is_array($child_url))
					{
						$route['link'] = sprintf('/%s%s', ( ! empty($route['map'])) ? sprintf('%s/', $route['map']) : '', $child_url[0]);
						$route['win_target'] = $child_url[1];
					}
					else
					{
						$route['link'] = sprintf('/%s%s', ( ! empty($route['map'])) ? sprintf('%s/', $route['map']) : '', $child_url);
					}
				}
			}
			else
			{
				$route['link'] = sprintf('/%s%s', ( ! empty($route['map'])) ? sprintf('%s/', $route['map']) : '', $route['url']);
			}

			if( ! empty($files[$route['id']]['files']))
			{
				$route = array_merge($route, $files[$route['id']]);
			}
			$this->menu[] = $this->menus[$route['menu_id']][$route['parent_id']][] = $route;
		}

		$maps = array_unique($maps);
		foreach($maps as $map)
		{
			$routes[sprintf('%s/(:any)', $map)] = '$1';
		}

		$RTX = & load_class('Router', 'core');

		$RTX->menu = $this->menu;
		$RTX->menus = $this->menus;
		$RTX->map = array();

		foreach($map_list as $map)
		{
			if( ! empty($map))
			{
				$RTX->map[] = $map;
			}
		}

		$DB_ROUTES = $routes;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @return	void
	 */
	public function setup()
	{
		$this->CI = &get_instance();

		$this->_set_enable_profiler();

		if (isset($_SERVER['HTTP_HOST']))
		{
			$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
			$base_url .= '://'. $_SERVER['HTTP_HOST'];
		}
		define('SITE', trim(str_replace($base_url, '', site_url()), '/'));
		$this->CI->config->set_item('base_url', str_replace(SITE, '', site_url()));

		$login_url = '';
		$login_page = '';
		$menu = $this->CI->menu->all();
		foreach($menu as $item)
		{
			if(in_array($item['target'], array('member/accounts/index', 'member/accounts/admin_login')) AND $this->CI->link->base['map'] == $item['map'])
			{
				$login_url = $item['url'];
				list($module, $controller, $action) = explode('/', $item['target']);
				$login_page = $this->CI->link->get(array('map'=>$item['map'], 'prefix'=>$item['url'], 'module'=>'member', 'controller'=>'accounts', 'action'=>$action, 'redirect'=>encode_url()));
				break;
			}
		}
		$this->CI->menu->login_page = $login_page;

		if(($this->CI->menu->current['isadmin'] == 'Y' AND $this->CI->account->is_logged() == FALSE))
		{
			if( ! empty($login_page))
			{
				if($this->CI->menu->current['url'] != $login_url)
				{
					redirect($login_page);
				}
			}
			else
			{
				script('ment', '로그인 페이지가 설정되지 않았습니다.', NULL, TRUE);
			}
		}
		else 
		{
			if($this->CI->menu->current['isadmin'] == 'Y' AND $this->CI->account->get('admin') != 'Y')
			{
				redirect('/');
			}
		}

	}

	// --------------------------------------------------------------------

	/**
	 * Enable profiler
	 *
	 * @access	private
	 * @return	void
	 */
	private function _set_enable_profiler()
	{
		if(is_ajax() != TRUE AND $this->CI->router->fetch_method() != 'js')
		{
			$this->CI->output->enable_profiler($this->CI->config->item('profiler'));
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Read FIle
	 *
	 * @access	private
	 * @return	string
	 */
	private function _read_file($file)
	{
		if ( ! file_exists($file))
		{
			return FALSE;
		}

		if (function_exists('file_get_contents'))
		{
			return file_get_contents($file);
		}

		if ( ! $fp = @fopen($file, FOPEN_READ))
		{
			return FALSE;
		}

		flock($fp, LOCK_SH);

		$data = '';
		if (filesize($file) > 0)
		{
			$data =& fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Read FIle
	 *
	 * @access	private
	 * @return	string
	 */
	private function _find_child_url($menus_recursive, $current)
	{
		if(empty($menus_recursive[$current['id']]))
		{
			return FALSE;
		}

		foreach($menus_recursive[$current['id']] as $item)
		{
			if( ! empty($item['link']))
			{
				return array($item['link'], $item['win_target']);
			}
			elseif( ! empty($item['url']))
			{
				return $item['url'];
			}
			return $this->_find_child_url($menus_recursive, $item);
		}
	}

}