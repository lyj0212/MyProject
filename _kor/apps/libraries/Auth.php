<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
	private $CI;
	private $option;

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function __construct()
	{
		log_message('debug', 'Auth Class Initialized');
		$this->CI =& get_instance();
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function admin($menu_id='')
	{
		if($this->CI->account->get('admin') == 'Y')
		{
			return TRUE;
		}

		$admin = $this->get_admin($menu_id);
		if(in_array($this->CI->account->get('id'), $admin))
		{
			return TRUE;
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	protected
	 * @params array
	 * @return	object
	 */
	public function check($params=array())
	{
		$this->option['prefix'] = $this->CI->menu->current['url'];
		$this->option['module'] = $this->CI->router->fetch_module();
		$this->option['controller'] = $this->CI->router->fetch_class();
		$this->option['action'] = $this->CI->router->fetch_method();
		$this->option['segment'] = array();
		$this->CI->_set($this->option, $params);

		$current_menu = $this->CI->menu->one($this->option['prefix']);

		// 최고관리자그룹
		if($this->admin($current_menu['id']) == TRUE)
		{
			return TRUE;
		}

		$target = $current_menu['target'];
		$menu_id = $current_menu['id'];

		$allow_action = array('convert', 'js');
		if(in_array($this->option['action'], $allow_action))
		{
			return TRUE;
		}

		$allow_method = array('member.accounts.logout', 'popup.popup.view');
		if(in_array(sprintf('%s.%s.%s', $this->option['module'], $this->option['controller'], $this->option['action']), $allow_method))
		{
			return TRUE;
		}

		// 캐쉬 삭제는 직접해야 합니다.
		$cache_name = sprintf('_module_config_%s', $this->option['module']);
		if( ! $arr = $this->CI->cache->get($cache_name))
		{
			$file = sprintf('./modules/%s/conf/module.xml', $this->option['module']);
			if(file_exists($file))
			{
				$conf = simplexml_load_file($file);
				$arr = object2array($conf);
				$this->CI->cache->save($cache_name, $arr, 9999999);
			}
		}

		if( ! empty($arr))
		{
			// 모듈 전체 권한 사용 여부 체크
			if(isset($arr['permission']) AND $arr['permission'] == 'N')
			{
				return TRUE;
			}

			if( ! isset($arr['section'][0]))
			{
				$arr['section'] = array($arr['section']);
			}

			$module_grant = array();
			foreach($arr['section'] as $item)
			{
				if($item['target'] == $target)
				{
					if(isset($item['permission']) AND $item['permission'] == 'N')
					{
						return TRUE;
					}

					if( ! isset($item['grants']['grant']))
					{
						break;
					}
					if( ! isset($item['grants']['grant'][0]))
					{
						$item['grants']['grant'] = array($item['grants']['grant']);
					}
					foreach($item['grants']['grant'] as $permission)
					{
						if( ! isset($permission['@attributes']))
						{
							$permission['@attributes'] = $permission;
						}
						$allow_list = explode('|', $permission['@attributes']['action']);

						foreach($allow_list as $allowed)
						{
							$allow = explode('.', $allowed);
							switch(count($allow))
							{
								case 3 :
									if($this->option['module'] == $allow[0] AND $this->option['controller'] == $allow[1] AND $this->option['action'] == $allow[2])
									{
										$module_grant = $permission;
										break 3;
									}
									break;
								case 2 :
									if($this->option['controller'] == $allow[0] AND $this->option['action'] == $allow[1])
									{
										$module_grant = $permission;
										break 3;
									}
									break;
								case 1 :
									if($this->option['action'] == $allow[0])
									{
										$module_grant = $permission;
										break 3;
									}
									break;
							}
						}
					}
					break;
				}
			}

			// 권한관리에 의한 권한 체크
			$model = array(
				'select' => 'type, action, group_id',
				'from' => 'permission',
				'conditions' => array('where' => array('menu_id' => $menu_id)),
				'enable_cache' => sprintf('_permission_%s', $menu_id)
			);
			$setuped = $this->CI->get_entries($model);

			$permissions = array();
			foreach($setuped['data'] as $item)
			{
				$in_action = explode('|', $item['action']);
				if(
					in_array($this->option['action'], $in_action) OR
					in_array(sprintf('%s.%s', $this->option['controller'], $this->option['action']), $in_action) OR
					in_array(sprintf('%s.%s.%s', $this->option['module'], $this->option['controller'], $this->option['action']), $in_action)
				)
				{
					$permissions[] = $item;
				}
			}

			if( ! empty($module_grant['option']))
			{
				$is_case = FALSE;
				foreach($module_grant['option'] as $options)
				{
					if( ! isset($options['@attributes']))
					{
						$options['@attributes'] = $options;
					}
					if( ! empty($options['@attributes']['case']))
					{
						$case = explode('.', $options['@attributes']['case']);
						if($case[0] == 'param')
						{
							if(isset($this->option['segment'][$case[1]]))
							{
								$segment = $this->option['segment'][$case[1]];
							}
							else
							{
								$segment = $this->CI->link->get_segment($case[1], FALSE);
							}
							if(empty($segment))
							{
								// case 조건에 만족하지 않으면 패스
								continue;
							}
							else
							{
								$is_case = TRUE;
							}
						}
					}

					if( ! empty($options['@attributes']['uncase']))
					{
						$uncase = explode('.', $options['@attributes']['uncase']);
						if($uncase[0] == 'param')
						{
							if(isset($this->option['segment'][$uncase[1]]))
							{
								$segment = $this->option['segment'][$uncase[1]];
							}
							else
							{
								$segment = $this->CI->link->get_segment($uncase[1], FALSE);
							}

							if( ! empty($segment))
							{
								// uncase 조건에 만족하지 않으면 패스
								continue;
							}
						}
					}

					if($is_case == FALSE AND empty($permissions) AND isset($options['@attributes']['default']) AND $options['@attributes']['default'] == 'allow')
					{
						return TRUE;
					}

					if( ! empty($options['@attributes']['query']))
					{
						$template_list = array('/{(.+)}/U');
						$sql = preg_replace_callback(
							$template_list,
							array(&$this, '_remodel_query'),
							$options['@attributes']['query']
						);

						$check = $this->CI->get_query($sql);
						if( ! empty($check))
						{
							return TRUE;
						}
					}
				}

				if($is_case == TRUE)
				{
					return FALSE;
				}
			}

			if( ! empty($permissions))
			{
				foreach($permissions as $item)
				{
					// 모든 사용자
					if($item['group_id'] == '__ALL__')
					{
						return TRUE;
					}

					// 그룹 체크
					if($item['type'] == 'group' AND $item['group_id'] == $this->CI->account->get('group'))
					{
						return TRUE;
					}

					// 부서 체크
					if($item['type'] == 'busu' AND $item['group_id'] == $this->CI->account->get('department'))
					{
						return TRUE;
					}

					// 회원 체크
					if($item['type'] == 'member' AND $item['group_id'] == $this->CI->account->get('id'))
					{
						return TRUE;
					}
				}
			}
			else
			{
				// 개별 권한이 없으면 모듈 기본 권한 체크
				if(isset($module_grant['@attributes']['default']) AND $module_grant['@attributes']['default'] == 'allow')
				{
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	protected
	 * @params array
	 * @return	object
	 */
	private function _remodel_query($matches)
	{
		global $CI;

		switch($matches[1])
		{
			case 'dbprefix' :
				return $CI->db->dbprefix;
				break;
			case 'memberid' :
				return ($CI->account->get('id')) ? $CI->account->get('id') : 0;
				break;
			default :
				$user_case = explode('.', $matches[1]);
				if($user_case[0] == 'param')
				{
					if(isset($this->option['segment'][$user_case[1]]))
					{
						$segment = $this->option['segment'][$user_case[1]];
					}
					else
					{
						$segment = $CI->link->get_segment($user_case[1], FALSE);
					}
					return ($segment) ? $segment : 0;
				}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	protected
	 * @params array
	 * @return	object
	 */
	public function get_admin($menu_id='')
	{
		if(empty($menu_id))
		{
			$menu_id = $this->CI->menu->current['id'];
		}

		$model = array(
			'select' => 'id',
			'from' => 'member_group',
			'conditions' => array('where' => array('admin' => 'Y')),
			'enable_cache' => '_member_group_admin'
		);
		$admin = $this->CI->get_row($model);

		if(empty($admin['data']))
		{
			return array();
		}

		$model = array(
			'select' => 'type, group_id',
			'from' => 'permission',
			'conditions' => array('where' => array('menu_id' => $menu_id, 'action' => '__ALL__', 'group_id !=' => $admin['data']['id'])),
			'enable_cache' => sprintf('_permission_admin_%s', $menu_id)
		);
		$admin_list = $this->CI->get_entries($model);

		$result = array();
		foreach($admin_list['data'] as $item)
		{
			if($item['type'] == 'group')
			{
				$model = array(
					'select' => 'id',
					'from' => 'member',
					'conditions' => array('where' => array('group' => $item['group_id'])),
					'enable_cache' => array(sprintf('_member_group_entries_%s', $item['group_id']), 600)
				);
				$entries = $this->CI->get_entries($model);

				foreach($entries['data'] as $user)
				{
					$result[] = $user['id'];
				}
			}
			elseif($item['type'] == 'busu')
			{
				$model = array(
					'select' => 'id',
					'from' => 'member',
					'conditions' => array('where' => array('department' => $item['group_id'])),
					'enable_cache' => array(sprintf('_member_department_entries_%s', $item['group_id']), 600)
				);
				$entries = $this->CI->get_entries($model);

				foreach($entries['data'] as $user)
				{
					$result[] = $user['id'];
				}
			}
			elseif($item['type'] == 'member')
			{
				$result[] = $item['group_id'];
			}
		}

		return array_unique($result);
	}

}
