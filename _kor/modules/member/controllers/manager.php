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
 * Manager Class
 *
 * 회원관리 클래스 입니다.
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
		//$this->cache->delete('module_config_member');
		$model = array(
			'from' => 'member A',
			'limit' => 10,
			'paging' => TRUE,
			'order' => array('id'=>'DESC'),
			'include_file' => TRUE,
			'include_search' => TRUE,
			'callback' => '_process_entries'
		);
		
		if($this->account->get('userid') != 'admin')
		{
			$model['conditions']['where'] = array('A.userid !=' => 'admin');
		}
		
		$data = $this->get_entries($model);

		$view = array(
			'skin' => 'manager/index',
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
	public function view()
	{
		$id = $this->link->get_segment('id');

		$model= array(
            'select' => array(
                'A.*',
                'C.title',
            ),
            'from' => 'member A',
            'join' => array(
                array('member_group C', 'A.group=C.id', 'left'),
                ),
            'conditions' => array('where' => array('id' => $id)),
			'include_file' => TRUE,
			'include_dynamic' => TRUE,
			'callback' => '_process_entries',
			'not_exists' => '_error_exists'
		);
		$data = $this->get_row($model);

		$view = array(
			'skin' => 'manager/view',
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
			$model= array(
				'from' => 'member A',
				'conditions' => array('where' => array('id' => $id)),
				'include_file' => 'ALL',
				'include_dynamic' => TRUE,
				'not_exists' => '_error_exists'
			);
			$data = $this->get_row($model);

			$data['data']['passwd'] = NULL;

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
			$data = $this->set_default(array('member'));
			$data['data']['join_date'] = date('Y-m-d');
			$data['data']['is_work'] = 'Y';
		}

		$model = array(
			'from' => 'member_group',
			'order' => array('sort' => 'DESC')
		);
		$data['group'] = $this->get_entries($model);

		$view = array(
			'skin' => 'manager/write',
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
		
		$this->form_validation->set_rules('name', '이름', 'trim|required');
		$this->form_validation->set_rules('userid', '아이디', 'trim|required');
		
		if(empty($id))
		{
			$this->form_validation->set_rules('passwd', '비밀번호', 'trim|required');
		}

		if ($this->form_validation->run() === FALSE)
		{
			$this->write();
			return FALSE;
		}

		if( ! empty($id))
		{
			$model= array(
				'from' => 'member',
				'conditions' => array('where' => array('id' => $id)),
				'not_exists' => '_error_exists'
			);
			$row = $this->get_row($model);
		}

		$data['userid'] = $this->input->post('userid');

		$model = array(
			'from' => 'member',
			'conditions' => array('where' => array('userid' => $data['userid']))
		);

		if( ! empty($id))
		{
			$model['conditions']['where'] = array_merge($model['conditions']['where'] , array('userid !=' => $row['data']['userid']));
		}

		$check = $this->check_exists($model);
		if($check == TRUE)
		{
			message('이미 등록된 아이디 입니다.', 'error');
			$this->write();
			return FALSE;
		}

		if($this->account->get('admin') == 'Y')
		{
			$data['group'] = $this->input->post('group');
		}

		$data['name'] = $this->input->post('name');

		if($this->input->post('passwd'))
		{
			$data['passwd'] = sha512($this->input->post('passwd'));
		}

		$model = array(
			'from' => 'member',
			'conditions' => ( ! empty($id)) ? array('where'=>array('id'=>$id)) : NULL,
			'data' => $data,
			'include_file' => TRUE,
			'include_dynamic' => TRUE
		);
		$affect_id = $this->save_row($model);

		if ($this->form_validation->run() === FALSE) {
			$this->write();
			return FALSE;
		}
		unset($data);

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
			'from' => 'member',
			'conditions' => array('where'=>array('id'=>$id))
		);
        $this->delete_row($model);

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
	public function userid_Check()
	{
        $userid = $this->input->post('id');
        
		$model = array(
            'select' => 'id',
			'from' => 'member',
			'conditions' => array('where'=>array('userid'=>$userid))
		);
        
		if($this->check_exists($model) == TRUE)
        {
            echo '이미 등록된 아이디가 있습니다.';
        }
        else 
        {
            echo 'true';
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
	protected function _error_exists()
	{
		script('back', '존재하지 않는 회원입니다.');
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
		// 사원번호
		$item['employee_num'] = $item['id'];

		// 생년월일
		if( ! empty($item['jumin2']))
		{
			$sex = substr($this->encrypt->decode($item['jumin2']), 0, 1);
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
		}

		$width = 200;
		$height = 150;

		$mini_width = 35;
		$mini_height = 35;

		$item['thumb']['member_picture'] = site_url($this->image_lib->noimg(70, 70));
		$item['thumb']['mini'] = site_url($this->image_lib->noimg($mini_width, $mini_height));

		if( ! empty($data['files'][$item['id']]))
		{
			foreach($data['files'][$item['id']] as $file)
			{
				$config = array(
					'source_image' => $file['upload_path'].$file['raw_name'].$file['file_ext'],
					'new_image' => sprintf('%sthumbs/%s_%s_%s%s', $file['upload_path'], $file['raw_name'], $width, $height, $file['file_ext']),
					'width' => $width,
					'height' => $height,
					'maintain_ratio' => TRUE
				);
				$item['thumb'][$file['target']] = site_url($this->image_lib->thumb($config));

				if($file['target'] == 'member_picture')
				{
					$config = array(
						'source_image' => $file['upload_path'].$file['raw_name'].$file['file_ext'],
						'new_image' => sprintf('%sthumbs/%s_%s_%s%s', $file['upload_path'], $file['raw_name'], $mini_width, $mini_height, $file['file_ext']),
						'width' => $mini_width,
						'height' => $mini_height,
						'maintain_ratio' => FALSE
					);
					$item['thumb']['mini'] = site_url($this->image_lib->thumb($config));
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
	protected function _process_department(&$item)
	{
		$this->department[$item['parent_id']][] = $item;
	}

}
