<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu
{
	private $CI;
	public $current = array();
	private $menu = array();
	private $menus = array();
	public $login_page = '';

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
		log_message('debug', 'Menu Class Initialized');
		$this->CI =& get_instance();

		$this->_initialize();
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	private function _initialize()
	{
		$this->menu = $this->CI->router->menu;
		$this->menus = $this->CI->router->menus;

		// cache enable
		$this->CI->load->driver('cache', array('adapter'=>'file', 'backup'=>'file'));

		$menu_field = $this->CI->set_default(array('menu', 'menu_item'));
		$this->current = $menu_field['data'];
		$is_current = FALSE;

		foreach($this->menu as $key=>&$item)
		{
			if($this->CI->link->base['map'] != $item['map'])
			{
				unset($this->menu[$key]);
				continue;
			}

			if( ! empty($item['files']))
			{
				foreach($item['files'] as $file)
				{
					$item[$file['target']] = site_url($file['upload_path'].$file['raw_name'].$file['file_ext']);
				}
			}

			if( ! empty($this->CI->link->base['prefix']) AND $item['url'] == $this->CI->link->base['prefix'])
			{
				$this->current = $item;
				$is_current = TRUE;
			}
			elseif(empty($this->CI->link->base['prefix']) AND $item['index'] == 'Y')
			{
				$this->current = $item;
				$is_current = TRUE;
			}
		}
		
		$this->current_parent_titles = $this->_get_parent_line($this->current);

		if($is_current == TRUE)
		{
			$this->CI->session->set_userdata('layout', $this->current['layout']);
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
	public function recursive()
	{
		return $this->menus;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function all()
	{
		return $this->menu;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function one($url)
	{
		foreach($this->all() as $item)
		{
			if($item['url'] == $url)
			{
				return $item;
			}
		}

		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function parents()
	{
		static $parents;

		if(is_array($parents))
		{
			return $parents;
		}

		if(empty($this->current['menu_id']))
		{
			return array();
		}

		$parents = $this->menus[$this->current['menu_id']][0];

		return $parents;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function has_parent_childs()
	{
		$childs = $this->parent_childs();
		if(count($childs) > 0)
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
	public function parent_childs()
	{
		static $childs;

		if(is_array($childs))
		{
			return $childs;
		}

		if( ! isset($this->current['menu_id']))
		{
			return array();
		}
		$parent = $this->_find_current_top();

		if(isset($this->menus[$this->current['menu_id']][$parent['id']]))
		{
			$childs = $this->menus[$this->current['menu_id']][$parent['id']];
		}
		return $childs;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function is_active($menu)
	{
		if($menu['url'] == $this->current['url'])
		{
			return TRUE;
		}

		if(isset($this->menus[$this->current['menu_id']][$menu['id']]))
		{
			$childs = $this->menus[$this->current['menu_id']][$menu['id']];
			foreach($childs as $item)
			{
				if($this->is_active($item))
				{
					return TRUE;
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
	public function _find_current_top($current=array())
	{
		if(empty($current))
		{
			$current = $this->current;
		}
		if( ! empty($current['parent_id']))
		{
			foreach($this->menu as $item)
			{
				if($item['id'] == $current['parent_id'])
				{
					return $this->_find_current_top($item);
				}
			}
		}
		else
		{
			return $current;
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
	public function navigation($params=array())
	{
		$option['home_tag'] = '<a href="/">Home</a>';
		$option['divider'] = '';
		$option['current'] = '<li class="active">%s</li>';

		$this->CI->_set($option, $params);
		$subs = $this->_get_parent_line($this->current);

		$html = array_merge(
			array(sprintf('<li>%s</li>', $option['home_tag'])),
			array(sprintf('<li>%s</li>', implode('</li><li>', $subs))),
			array(sprintf($option['current'], $this->current['title']))
		);

		return sprintf('<ol class="breadcrumb">%s</ol>', implode($option['divider'], $html));
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	private function _get_parent_line($current)
	{
		static $parents = array();

		if(empty($current['parent_id']))
		{
			return array_reverse($parents);
		}

		foreach($this->menu as $item)
		{
			if($current['parent_id'] == $item['id'])
			{
				$closest = $item;
				$parents[] = $item['title'];
				break;
			}
		}

		return $this->_get_parent_line($closest);
	}

}
