<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account
{
	private $CI;
	private $member = array();

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
		log_message('debug', 'Member Class Initialized');
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
		if(($logged = $this->is_logged()))
		{
			$this->CI->load->library('image_lib');

			$model = array(
				'select' => array(
					'A.*',
					'B.title as group_title, B.admin'
				),
				'from' => 'member A',
				'join' => array('member_group B', 'A.group=B.id'),
				'conditions' => array('where' => array('A.id' => $logged)),
				'include_file' => TRUE,
				'include_dynamic' => TRUE,
				'callback_object' => $this,
				'callback' => '_process_member',
				'not_exists' => '_error_member_exists'
			);
			$row = $this->CI->get_row($model);

			$is_login = 'Y';
			/*if($row['data']['is_login'] != $is_login)
			{
				$model = array(
					'from' => 'member',
					'conditions' => array('where' => array('id' => $row['data']['id'])),
					'data' => array('is_login' => $is_login),
					'include_time' => FALSE
				);
				$this->CI->save_row($model);
				$row['data']['is_login'] = $is_login;
			}*/
			$this->_set($row['data']);

			// 피드 데이터
			/*
			$model = array(
				'select' => 'A.*, B.name',
				'from' => 'feed A',
				'join' => array('member B', 'A.sender=B.id'),
				'conditions' => array('where' => array('ismember' => $logged)),
				'limit' => 5,
				'order' => array('created' => 'DESC'),
				'enable_cache' => sprintf('feed_%s', $logged)
			);
			$feeds = $this->CI->get_entries($model);
			$this->set('feeds', $feeds['data']);

			$model = array(
				'select' => 'COUNT(A.id) as total_feeds',
				'from' => 'feed A',
				'join' => array('member B', 'A.sender=B.id'),
				'conditions' => array('where' => array('ismember' => $logged, 'is_view' => 'N')),
				'enable_cache' => sprintf('feed_total_%s', $logged)
			);
			$total_feeds = $this->CI->get_row($model);
			$this->set('total_feeds', $total_feeds['data']['total_feeds']);
			*/
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
	public function get($name)
	{
		if(strpos($name, '.') !== FALSE)
		{
			$value = explode('.', $name);

			if(isset($this->member[$value[0]][$value[1]]))
			{
				return $this->member[$value[0]][$value[1]];
			}
			else
			{
				return FALSE;
			}
		}

		if(isset($this->member[$name]))
		{
			return $this->member[$name];
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
	private function _set($data)
	{
		unset($data['passwd']);
		$this->member = $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function set($name, $value)
	{
		$this->member[$name] = $value;
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function is_logged()
	{
		if(($logged = $this->CI->session->userdata('logged')))
		{
			return $logged;
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
	public function _error_member_exists()
	{
		$this->CI->session->sess_destroy();
		script('login', '회원정보를 불러오는 중 오류가 발생했습니다.\n다시 로그인 해주세요.');
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function _process_member(&$item, $data)
	{
		// 메신저 사용여부
		$item['messenger'] = 'true';

		// 전체 이름
		$item['fullname'] = $item['name'];

		// 사원번호
		$item['employee_num'] = $item['id'];

		// 전화번호
		//$item['phone'] = implode('-', array($item['phone1'], $item['phone2'], $item['phone3']));

		// 생년월일
		/*if( ! empty($item['jumin2']))
		{
			$sex = substr($this->CI->encrypt->decode($item['jumin2']), 0, 1);
			if(in_array($sex, array(9, 1, 3, 5, 7)))
			{
				$item['sex'] = '1';
			}
			else
			{
				$item['sex'] = '2';
			}
			if(in_array($sex, array(1, 2, 5, 6)))
			{
				$birth = sprintf('19%s', $item['jumin1']);
			}
			elseif(in_array($sex, array(3, 4, 7, 8)))
			{
				$birth = sprintf('20%s', $item['jumin1']);
			}
			elseif(in_array($sex, array(9, 0)))
			{
				$birth = sprintf('18%s', $item['jumin1']);
			}
			if( ! empty($birth))
			{
				$item['birth'] = sprintf('%s-%s-%s', substr($birth, 0, 4), substr($birth, 4, 2), substr($birth, 6, 2));
			}
		}*/

		$width = 90;
		$height = 110;

		$mini_width = 35;
		$mini_height = 35;

		$item['thumb']['member_picture'] = site_url($this->CI->image_lib->noimg($width, $height));
		$item['thumb']['mini'] = site_url($this->CI->image_lib->noimg($mini_width, $mini_height));

		if( ! empty($data['files'][$item['id']]))
		{
			foreach($data['files'][$item['id']] as $file)
			{
				$config = array(
					'source_image' => $file['upload_path'].$file['raw_name'].$file['file_ext'],
					'new_image' => sprintf('%sthumbs/%s_%s_%s%s', $file['upload_path'], $file['raw_name'], $width, $height, $file['file_ext']),
					'width' => $width,
					'height' => $height,
					'maintain_ratio' => FALSE
				);
				$item['thumb'][$file['target']] = site_url($this->CI->image_lib->thumb($config));

				if($file['target'] == 'member_picture')
				{
					$config = array(
						'source_image' => $file['upload_path'].$file['raw_name'].$file['file_ext'],
						'new_image' => sprintf('%sthumbs/%s_%s_%s%s', $file['upload_path'], $file['raw_name'], $mini_width, $mini_height, $file['file_ext']),
						'width' => $mini_width,
						'height' => $mini_height,
						'maintain_ratio' => FALSE
					);
					$item['thumb']['mini'] = site_url($this->CI->image_lib->thumb($config));
				}
			}
		}
	}

}
