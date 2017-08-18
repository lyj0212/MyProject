<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout {

	private $CI;

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @return	void
	 */
	public function set_layout()
	{
		$this->CI =& get_instance();

		// 모듈에 속해있지 않은 뷰는 그냥 출력
		if( ! isset($this->CI->load->_ci_view_path))
		{
			$this->CI->output->_display();
			return TRUE;
		}

		// 모듈 스크립트 첨부
		$data['module_script'] = sprintf('<script src="%s"></script>', $this->CI->link->get(array('action'=>'js', 't'=>time())))."\n";

		// 휘발성 메세지 첨부
		$data['content_for_layout'] = '';
		if($messages = $this->CI->session->userdata('flash_message'))
		{
			foreach($messages as $style=>$value)
			{
				foreach($value as $message)
				{
					$data['content_for_layout'] .= sprintf(
						'<div class="alert alert-%s"><button type="button" class="close" data-dismiss="alert">&times;</button>%s</div>',
						$style,
						$message
					);
				}
			}
			$this->CI->session->unset_userdata('flash_message');
		}

		// 모듈 view
		$data['content_for_layout'] .= $this->CI->output->get_output();

		if( ! empty($this->CI->layout) AND $this->CI->layout != 'popup')
		{
			$result_view = $this->CI->load->view('../../'.$this->CI->layout, $data, TRUE);
		}
		elseif ($this->CI->layout === FALSE)
		{
			$result_view = $data['content_for_layout'];
		}
		else
		{
			$layout_id = $this->CI->menu->current['layout'];
			if(empty($layout_id) AND $this->CI->layout == 'popup' AND $this->CI->session->userdata('layout'))
			{
				$layout_id = $this->CI->session->userdata('layout');
			}

			if( ! empty($layout_id))
			{
				if( ! $layout = $this->CI->cache->get(sprintf('layout_%s', $layout_id)))
				{
					$model = array(
						'select' => 'source, top_menu, top_menu_depth, sub_menu, sub_menu_depth, popup',
						'from' => 'layouts',
						'conditions' => array('where' => array('id' => $layout_id))
					);
					$layout = $this->CI->get_row($model);
					$this->CI->cache->save(sprintf('layout_%s', $layout_id), $layout, 8640000);
				}

				if($this->CI->layout == 'popup')
				{
					if( ! empty($layout['data']['popup']))
					{
						$result_view = $this->CI->load->view('../../'.$layout['data']['popup'], $data, TRUE);
					}
					else
					{
						$result_view = $this->CI->load->view('../../manager/layout/popup/popup', $data, TRUE);
					}
				}
				else
				{
					if($this->CI->menu->current['isadmin'] == 'Y')
					{
						if(empty($layout['data']['source']))
						{
							$this->CI->load->library('template');
							$this->CI->template->compile_dir = FCPATH.'attach/layout';
							$layout['data']['source'] = 'manager/layout/layout';
						}

						if(empty($layout['data']['top_menu']))
						{
							$layout['data']['top_menu'] = 'manager/layout/menu/top_menu.php';
						}

						if(empty($layout['data']['top_menu_depth']))
						{
							$layout['data']['top_menu_depth'] = 'manager/layout/menu/top_menu_depth.php';
						}

						if(empty($layout['data']['sub_menu']))
						{
							$layout['data']['sub_menu'] = 'manager/layout/menu/sub_menu.php';
						}

						if(empty($layout['data']['sub_menu_depth']))
						{
							$layout['data']['sub_menu_depth'] = 'manager/layout/menu/sub_menu_depth.php';
						}
					}

					$data['topmenu'] = '';
					$data['submenu'] = '';
					if( ! empty($layout['data']['source']))
					{
						$parent_menu = $this->CI->menu->parents();
						foreach($parent_menu as $i=>&$item)
						{
							$data['classnum'][$item['id']] = $i;
							if($item['hidden'] == 'Y')
							{
								if($item['target'] != 'member/accounts/admin_login' && $this->CI->account->get('userid') == 'admin' && $this->CI->menu->current['isadmin'] == 'Y') 
								{
									continue;
								}
								else
								{ 
									unset($parent_menu[$i]);
									continue;
								}
							}
							$item['sub_entries'] = $this->__construct_menu($item['id'], $layout['data']['top_menu_depth']);
						}
						$data['topmenu'] = $this->__process_template_menu($parent_menu, $layout['data']['top_menu']);

						if($this->CI->menu->has_parent_childs())
						{
							$sub_menu = $this->CI->menu->parent_childs();
							foreach($sub_menu as $i=>&$item)
							{
								if($item['hidden'] == 'Y')
								{
									if($this->CI->account->get('userid') == 'admin' && $this->CI->menu->current['isadmin'] == 'Y') 
									{
										continue;
									}
									else 
									{
										unset($sub_menu[$i]);
										continue;
									}
								}
								$item['sub_entries'] = $this->__construct_menu($item['id'], $layout['data']['sub_menu_depth']);
							}
							$data['submenu'] = $this->__process_template_menu($sub_menu, $layout['data']['sub_menu']);
						}

						$result_view = $this->CI->load->view('../../'.$layout['data']['source'], $data, TRUE);
					}
				}
			}
			else
			{
				$result_view = $data['content_for_layout'];
			}
		}

		if(is_ajax() != TRUE)
		{
			$this->CI->html['css'] = array_unique_recursive($this->CI->html['css']);
			$this->CI->html['js'] = array_unique_recursive($this->CI->html['js']);
			$head_script = $this->CI->load->view('common/head_script', $data, TRUE);
			$footer_script = $this->CI->load->view('common/foot_script', $data, TRUE);

			if (preg_match("|(.*?)</head>(.*)|is", $result_view, $matches))
			{
				$result_view  = $matches[1];
				$result_view .= $head_script;
				$result_view .= "</head>";
				$result_view .= $matches[2];
			}
			else
			{
				$result_view = $head_script.$result_view;
			}

			if (preg_match("|</body>.*?</html>|is", $result_view))
			{
				$result_view  = preg_replace("|</body>.*?</html>|is", '', $result_view);
				$result_view .= $footer_script;
				$result_view .= "\n\n</body>\n</html>";
			}
			else
			{
				$result_view .= $footer_script;
			}
		}

		$session_data = array();
		$session_data['scriptdata'] = encode_url($this->CI->view_data);
		$this->_write_session($session_data);

		$this->CI->output->set_output($result_view);
		$this->CI->output->_display();
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	private function _write_session($data)
	{
		$this->CI->session->set_userdata($data);

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
	private function __construct_menu($parent, $menu_template_path, $depth=0)
	{
		$menus = $this->CI->menu->recursive();

		if(isset($menus[$this->CI->menu->current['menu_id']][$parent]))
		{
			$submenu = $menus[$this->CI->menu->current['menu_id']][$parent];
		}
		else
		{
			return FALSE;
		}

		$depth++;
		foreach($submenu as $i=>&$item)
		{
			if($item['hidden'] == 'Y')
			{
				if($this->CI->account->get('userid') == 'admin' && $this->CI->menu->current['isadmin'] == 'Y') 
				{
					continue;
				}
				else 
				{
					unset($submenu[$i]);
					continue;
				}
			}
			$item['sub_entries'] = $this->__construct_menu($item['id'], $menu_template_path, $depth);
		}

		return $this->__process_template_menu($submenu, $menu_template_path, $depth);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	private function __process_template_menu($menus, $menu_template_path, $depth=0)
	{
		$active = FALSE;
		foreach($menus as &$item)
		{
			$current_top = $this->CI->menu->_find_current_top();
			$item['n'] = '';
			$item['active'] = $this->CI->menu->is_active($item);
			if($item['active'] == TRUE)
			{
				$active = TRUE;
			}

			if( ! empty($item['files']))
			{
				foreach($item['files'] as $file)
				{
					$item[$file['target']] = site_url($file['upload_path'].$file['raw_name'].$file['file_ext']);
				}
			}
			$item = array_change_key_case($item, CASE_UPPER);
		}

		$this->CI->load->library('template');
		$this->CI->template->define('submenu', $menu_template_path);
		$this->CI->template->assign('LOOP', $menus);
		$this->CI->template->assign('ACTIVE', $active);
		$this->CI->template->assign('DEPTH', $depth);

		return $this->CI->template->fetch('submenu');
	}

}
