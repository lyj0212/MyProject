<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Link
{
	private $CI;
	private $url;
	public $base;

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
		log_message('debug', "Link Class Initialized");

		$this->CI =& get_instance();
		$this->reset();
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	private function set($param, $only=FALSE)
	{
		$this->reset();
		if( ! is_array($param))
		{
			return FALSE;
		}
		if($only == TRUE)
		{
			// unset
			unset($this->url);
			$this->url = array();
		}

		if(isset($param['module']) AND $param['module'] != $this->base['module'] AND empty($param['prefix']))
		{
			$this->base['prefix'] = '';
		}

		foreach($param as $key=>$value)
		{
			switch($key)
			{
				case 'map' :
					$this->base['map'] = $value;
					break;
				case 'prefix' :
					$this->base['prefix'] = $value;
					break;
				case 'module' :
					$this->base['module'] = $value;
					break;
				case 'controller' :
					$this->base['controller'] = $value;
					break;
				case 'action' :
					$this->base['action'] = $value;
					break;
				default :
					if(empty($value) OR $value == FALSE)
					{
						unset($this->url[$key]);
					}
					else
					{
						$this->url[$key] = $value;
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
	public function get($param=array(), $only=FALSE)
	{
		if(count($param) > 0)
		{
			$this->set($param, $only);
		}
		else
		{
			$this->reset();
			if($only==TRUE)
			{
				// unset
				unset($this->url);
				$this->url = array();
			}
		}

		if((empty($param['module']) AND ! empty($this->base['prefix'])) OR ( ! empty($param['module']) AND $param['module'] == $this->base['module'] AND ! empty($this->base['prefix'])))
		{
			$this->base['module'] = '';
		}

		$link = sprintf('%s%s%s%s%s%s', ( ! empty($this->base['map'])) ? '/'.$this->base['map'] : '', ( ! empty($this->base['prefix'])) ? '/'.$this->base['prefix'] : '', ( ! empty($this->base['module'])) ? '/'.$this->base['module'] : '', ( ! empty($this->base['controller'])) ? '/'.$this->base['controller'] : '', ( ! empty($this->base['action'])) ? '/'.$this->base['action'] : '', (count($this->url) > 0) ? '/'.$this->CI->uri->assoc_to_uri($this->url) : '');
		return $link;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function reset()
	{
		if(in_array($this->CI->uri->segment(1), $this->CI->router->map))
		{
			$this->base['map'] = $this->CI->uri->segment(1);
			$this->base['prefix'] = ( ! empty($this->CI->menu->current['url'])) ? $this->CI->menu->current['url'] : $this->CI->uri->segment(2);
		}
		else
		{
			$this->base['map'] = '';
			$this->base['prefix'] = ( ! empty($this->CI->menu->current['url'])) ? $this->CI->menu->current['url'] : $this->CI->uri->segment(1);
		}
		$this->base['module'] = $this->CI->router->fetch_module();
		$this->base['controller'] = $this->CI->router->fetch_class();
		$this->base['action'] = $this->CI->router->fetch_method();
		$this->url = $this->CI->uri->ruri_to_assoc(3);

		foreach($this->url as $key=>$value)
		{
			if(empty($value) OR $value == FALSE)
			{
				unset($this->url[$key]);
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
	public function get_segment($param, $require=TRUE)
	{
		$this->reset();
		if( ! empty($this->url[$param]))
		{
			return $this->url[$param];
		}
		else
		{
			if($require == TRUE)
			{
				script('back', sprintf('%s [%s]', '필요한 시그먼트가 없습니다.', $param));
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
	public function check_segment_exists($value)
	{
		$this->reset();
		foreach($this->url as $segment)
		{
			if(decode_url($segment) == $value)
			{
				return TRUE;
			}
		}
	}

}
