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
 * Business Class
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
		$this->html['css'][] = './modules/apply/css/style.css';
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
		//$this->cache->delete('module_config_apply');
		$model = array(
			'select' => array(
				'A.*',
				'B.compname, B.comp_num, B.ceo_name',
				'B.business_status, B.business_type, B.content, B.homepage',
				'C.subject',
				'D.title as category_title'
			),
			'from' => 'apply A',
			'join' => array(
				array('apply_company_info B', 'A.id=B.apply_id'),
				array('business C', 'A.pid=C.id'),
				array('business_category D', 'A.category=D.id'),
			),
			'conditions' => array(
				'where' => array('A.state >' => 1)
			),
			'limit' => 10,
			'paging' => TRUE,
			'paging_action' => 'index',
			'order' => array('A.id'=>'DESC'),
			'include_file' => TRUE
		);
		$data = $this->get_entries($model);
		
		$search_category = get_field('search_category'); 
		$search_category = $this->db->escape_like_str($search_category);
		
		if(!empty($search_category) && $search_category != 'all')
		{
			$model['conditions']['where']['A.category'] = $search_category;
		}
		
		$search_field = get_field('search_field');
		$search_field = $this->db->escape_like_str($search_field);
		$search_keyword = get_field('search_keyword');
		$search_keyword = $this->db->escape_like_str($search_keyword);

		if(!empty($search_field) && !empty($search_keyword)) {
			if($search_field == 'all')
			{
				$model['conditions']['where'][] = array('(C.subject like \'%'.trim($search_keyword).'%\' OR A.chief_name like \'%'.trim($search_keyword).'%\' OR B.compname like \'%'.trim($search_keyword).'%\')');
			}
			else
			{ 
				switch($search_field)
				{
					case 'subject' :
						$search_field = 'C.subject';
						break;
					case 'name' :
						$search_field = 'A.chief_name';
						break;
					case 'compname' :
						$search_field = 'B.compname';
						break;
					default :
						$search_field = 'C.subject';
				}
				$model['conditions']['where'][] = array($search_field.' like \'%'.trim($search_keyword).'%\'');
			}
		}
		
		$search_subject = get_field('search_subject');
		$search_subject = $this->db->escape_like_str($search_subject);
		
		if(!empty($search_subject)) 
		{
			$model['conditions']['where']['A.pid'] = trim($search_subject);
		}
		
		$data['data'] = $this->get_entries($model);
		
		$model = array(
			'from' => 'business_category',
			'order' => array('sort' => 'ASC')
		);
		$data['category'] = $this->get_entries($model);
		
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
	public function write()
	{
		$id = $this->link->get_segment('id', FALSE);
		//echo $this->account->get('id');

		if( ! empty($id))
		{
			$model = array(
				'select' => array(
					'A.*',
					'B.id as bid, B.compname, B.comp_num, B.found_day, B.corporate_num, B.comp_status1, B.comp_status2, B.comp_status3, B.venture_ap, B.venture_date, B.venture_num, B.listed_check, B.listed_day, B.attached_lab',
					'B.attached_addr, B.ceo_name, B.comp_email, B.zipcode, B.address1, B.address2, B.address_status, B.phone, B.phone1, B.phone2, B.phone3, B.fax, B.fax1, B.fax2, B.fax3',
					'B.business_status, B.business_type, B.content, B.homepage',
					'C.userid',
				),
				'from' => 'apply A',
				'join' => array( 
					array('apply_company_info B', 'A.id=B.apply_id'),
					array('member C', 'A.ismember=C.id'),
				),
				'conditions' => array(
					'where' => array('id' => $id)
				),
				'not_exists' => '_error_exists',
				'callback' => '_process_apply_write_entries',
				'include_file' => TRUE,
				'include_company' => FALSE
			);
			$data = $this->get_row($model);
			
			$temp = array('corporate_num', 'comp_num', 'venture_num');
			foreach ($temp as $item) 
			{
				if(strpos($data['data'][$item], '-') !== false) 
				{
					$arr = explode('-',$data['data'][$item]);
					for($i=0; $i<3; $i++)
					{
						$data['data'][$item.($i+1)] = (!empty($arr[$i]) ? $arr[$i] : '');
					}
				}
				else
				{
					$data['data'][$item.'1'] = $data['data'][$item];
					$data['data'][$item.'2'] = '';
					$data['data'][$item.'3'] = '';
				}
			}

			$model = array(
				'select' => array(
					'id, subject',
					array('CASE WHEN (startdate > now() OR enddate < now() OR state = \'2\') THEN \'마감\' ELSE \'진행중\' END AS new_state', FALSE)
				),
				'from' => 'business',
				'conditions' => array('where'=>array('category'=>$data['data']['category'])),
				'include_company' => FALSE,
				'order' => array('new_state' => 'DESC', 'id' => 'DESC')
			);
			$data['subject'] = $this->get_entries($model);
			
		}
		else
		{
			$data = $this->set_default(array('apply', 'company_info'));
			$data['data']['userid'] = '';
			$data['data'] = array_merge($data['data'], array('comp_num1'=>'','comp_num2'=>'','comp_num3'=>'','corporate_num1'=>'','corporate_num2'=>'','corporate_num3'=>'', 'venture_num1'=>'', 'venture_num2'=>'', 'venture_num3'=>''));
		}
		
		$model = array(
			'from' => 'business_category',
			'order' => array('sort' => 'ASC')
		);
		$data['category'] = $this->get_entries($model);

		$this->html['css'][] = '/_res/libs/codemirror/codemirror.css';
		$this->html['js'][] = '/_res/libs/codemirror/codemirror.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/xml/xml.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/javascript/javascript.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/css/css.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/clike/clike.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/php/php.js';

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
		$this->form_validation->set_rules('category', '사업분류', 'trim|required');
		$this->form_validation->set_rules('subject', '사업공고', 'trim|required');
		$this->form_validation->set_rules('userid', '아이디', 'trim|required');

		if($this->form_validation->run() === FALSE)
		{
			$this->write();
			return FALSE;
		}

		$id = $this->input->post('id');
		$ismember = $this->input->post('ismember');
		$modify_count = $this->input->post('modify_count');
		
		//사업공고 정보
		$data['ismember'] = $ismember;
		$data['pid'] = $this->input->post('subject');
		$data['category'] = $this->input->post('category');
		
		// 사업신청여부 체크
		if(empty($id)) 
		{
			$model = array(
				'from' => 'apply',
				'conditions' => array('where' => array('ismember' => $ismember, 'pid' => $data['pid'])),
				'include_company' => FALSE
			);
			$row = $this->get_row($model);
			
			if((int)$row['data']['id'] > 0)
			{
				script('redirect', '선택하신 사업공고에 이미 접수하였습니다.', $this->link->get(array('action'=>'view', 'id'=>$row['data']['id'])));
				return FALSE;
			}
		}
		
		// 담당자 정보
		$data['chief_name'] = trim($this->input->post('chief_name'));
		$data['chief_phone1'] = trim($this->input->post('chief_phone1'));
		$data['chief_phone2'] = trim($this->input->post('chief_phone2'));
		$data['chief_phone3'] = trim($this->input->post('chief_phone3'));
		$data['chief_email'] = trim($this->input->post('chief_email'));
		$data['state'] = 2; //1임시저장/2:접수/3:승인
		if(!empty($id))
		{
			$data['modify_count'] = $modify_count+1;
		}
		else 
		{
			$data['ip'] = $this->input->ip_address();
		}
		
		$model = array(
			'from' => 'apply',
			'conditions' => ( ! empty($id)) ? array('where'=>array('id'=>$id)) : NULL,
			'data' => $data,
			'include_file' => TRUE,
			'include_dynamic' => TRUE
		);
		$affect_id = $this->save_row($model);
		
		// 기업 정보
		unset($data);
		$data['ismember'] = $ismember;
		$data['compname'] = $this->input->post('compname');
		$data['found_day'] = $this->input->post('found_day');
		$data['comp_num'] = $this->input->post('comp_num1').($this->input->post('comp_num2') == '' ? '' : '-'.$this->input->post('comp_num2')).($this->input->post('comp_num3') == '' ? '' : '-'.$this->input->post('comp_num3'));
		$data['corporate_num'] = $this->input->post('corporate_num1').($this->input->post('corporate_num2') == '' ? '' : '-'.$this->input->post('corporate_num2')).($this->input->post('corporate_num3') == '' ? '' : '-'.$this->input->post('corporate_num3'));
		$data['comp_status1'] = $this->input->post('comp_status1');
		$data['comp_status2'] = $this->input->post('comp_status2');
		$data['comp_status3'] = $this->input->post('comp_status3');
		$data['venture_ap'] = $this->input->post('venture_ap');
		$data['venture_date'] = $this->input->post('venture_date');
		$data['venture_num'] = $this->input->post('venture_num1').($this->input->post('venture_num2') =='' ? '' : '-'.$this->input->post('venture_num2')).($this->input->post('venture_num3') == '' ? '' : '-'.$this->input->post('venture_num3'));
		$data['listed_check'] = $this->input->post('listed_check');
		$data['listed_day'] = $this->input->post('listed_day');
		$data['attached_lab'] = $this->input->post('attached_lab');
		$data['attached_addr']  = $this->input->post('attached_addr');
		$data['ceo_name'] = $this->input->post('ceo_name');
		$data['comp_email'] = $this->input->post('comp_email');
		$data['zipcode'] = $this->input->post('zipcode');
		$data['address1'] = $this->input->post('address1');
		$data['address2'] = $this->input->post('address2');
		$data['address_status'] = $this->input->post('address_status');
		$data['phone1'] = $this->input->post('phone1');
		$data['phone2'] = $this->input->post('phone2');
		$data['phone3'] = $this->input->post('phone3');
		$data['phone'] = $this->input->post('phone1').'-'.$this->input->post('phone2').'-'.$this->input->post('phone3');
		$data['fax1'] = $this->input->post('fax1');
		$data['fax2'] = $this->input->post('fax2');
		$data['fax3'] = $this->input->post('fax3');
		$data['fax'] = $this->input->post('fax1').'-'.$this->input->post('fax2').'-'.$this->input->post('fax3');
		$data['business_status'] = $this->input->post('business_status');
		$data['business_type'] = $this->input->post('business_type');
		$data['content'] = $this->input->post('content');
		$data['homepage'] = $this->input->post('homepage');
		
		// 마이페이지 기업정보
		$model = array(
			'from' => 'company_info',
			'conditions' => array('where' => array('ismember' => $ismember)),
			'data' => $data,
			'update_readed' => TRUE
		);
		if($this->check_exists($model) == FALSE)
		{
			unset($model['conditions']);
		}
		$this->save_row($model);
		
		// 접수관리 기업정보
		$data['apply_id'] = $affect_id;
		$model = array(
			'from' => 'apply_company_info',
			'conditions' => array('where' => array('ismember' => $ismember, 'apply_id' => $affect_id)),
			'data' => $data,
			'update_readed' => TRUE
		);
		if($this->check_exists($model) == FALSE)
		{
			unset($model['conditions']);
		}
		$this->save_row($model);


		$addData = $this->input->post('add_file'); //추가 사업 서류명
		// 파일 업로드
		if( ! empty($_FILES['add_file']['name']))
		{
			foreach($_FILES['add_file']['name'] as $key => $item)
			{
				foreach($item as $k => $value)
				{
					//if(count($_FILES['add_file']['name'][$key][$k]) == 0)
					if(!empty($value))
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
			
						$chk_ext = strtolower(substr(strrchr($_FILES['add_file']['name'][$key][$k], '.'), 1));
						if( ! in_array($chk_ext, $allow_ext))
						{
							$this->_exception('허용되지 않은 확장자 입니다.');
						}
			
						$_FILES['tempfile']['name'] = $_FILES['add_file']['name'][$key][$k];
						$_FILES['tempfile']['type'] = $_FILES['add_file']['type'][$key][$k];
						$_FILES['tempfile']['tmp_name'] = $_FILES['add_file']['tmp_name'][$key][$k];
						$_FILES['tempfile']['error'] = $_FILES['add_file']['error'][$key][$k];
						$_FILES['tempfile']['size'] = $_FILES['add_file']['size'][$key][$k];
						
						if( ! $this->upload->do_upload('tempfile'))
						{
							$this->_exception($this->upload->display_errors());
						}
						else
						{
							$upload_data = $this->upload->data();
							
							if($key != 'add_file')
							{
								$model = array(
									'from' => 'files',
									'conditions' => array('where' => array('pid' => $affect_id,'target' => $key)),
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
	
							$data = array(
								'pid' => $affect_id,
								'target' => $key,
								'orig_name' => $upload_data['orig_name'],
								'raw_name' => $upload_data['raw_name'],
								'file_ext' => $upload_data['file_ext'],
								'file_size' => $upload_data['file_size']*1024,
								'upload_path' => $config['upload_path'],
								'use' => 'Y'
							);
							
							unset($data['file_text']);
							if($key == 'add_file') 
							{
								$data['file_text'] = $addData['add_file_text'][$k];
							}
							
							$model = array(
								'from' => 'files',
								'data' => $data
							);
							$fileID = $this->save_row($model);
						} 
					 }
				}
			}
		}

		message('저장 되었습니다.');
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
			'from' => 'apply',
			'conditions' => array('where' => array('id' => $id)),
			'not_exists' => '_error_exists'
		);
		$item = $this->get_row($model);

		$model = array(
			'from' => 'business',
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
	public function view()
	{
		$id = $this->link->get_segment('id');
		
		$model = array(
			'select' => array(
				'A.*',
				'B.id as bid, B.compname, B.comp_num, B.found_day, B.corporate_num, B.comp_status1, B.comp_status2, B.comp_status3, B.venture_ap, B.venture_date, B.venture_num, B.listed_check, B.listed_day, B.attached_lab',
				'B.attached_addr, B.ceo_name, B.comp_email, B.zipcode, B.address1, B.address2, B.address_status, B.phone, B.phone1, B.phone2, B.phone3, B.fax, B.fax1, B.fax2, B.fax3',
				'B.business_status, B.business_type, B.content, B.homepage',
				'C.userid',
			),
			'from' => 'apply A',
			'join' => array( 
				array('apply_company_info B', 'A.id=B.apply_id'),
				array('member C', 'A.ismember=C.id'),
			),
			'conditions' => array(
				'where' => array('id' => $id)
			),
			'hasmany' => array(
				array(
					'pkey' => 'ismember',
					'match' => 'pid',
					'from' => 'files',
					'conditions' => array('where' => array('use' => 'Y')),
					'return' => 'files',
					'include_company' => FALSE
				)
			),
			'include_file' => TRUE,
			'include_schedule' => TRUE,
			'update_readed'  => TRUE,
			'callback' => '_process_apply_view_entries',
			'not_exists' => '_error_exists'
		);
		$data = $this->get_row($model);
		
		$hit = $this->session->userdata('hit');

		if(empty($hit))
		{
			$hit = array();
		}
		if( ! in_array($id, $hit))
		{
			$model = array(
				'from' => 'apply',
				'conditions' => array('where' => array('id' => $id)),
				'data' => array('hit' => $data['data']['hit']+1),
				'include_time' => FALSE
			);
			$this->save_row($model);

			$hit[] = $id;
			$this->session->set_userdata('hit', $hit);
		}
		
		// 해당 회원이 신청한 모든 사업분류 가져오기
		$model = array(
			'select' => array(
				'DISTINCT(B.id), B.title',
			),
			'from' => 'apply A',
			'join' => array(
				array('business_category B', 'A.category=B.id'),
			),
			'conditions' => array(
				'where' => array('A.ismember' => $data['data']['ismember'])
			)
		);
		$data['myCategory'] = $this->get_entries($model);
		
		$model = array(
			'select' => array(
				'A.id as apply_id',
				'B.id,B.subject',
				array('CASE WHEN (B.startdate > now() OR B.enddate < now() OR B.state = \'2\') THEN \'마감\' ELSE \'진행중\' END AS new_state', FALSE)
			),
			'from' => 'apply A',
			'join' => array(
				array('business B', 'A.pid=B.id'),
			),
			'conditions' => array(
				'where' => array('ismember'=> $data['data']['ismember'], 'category' => $data['data']['category'], 'A.state >' => 1)
			)
		);
		$data['myBusiness'] = $this->get_entries($model);

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
	public function change_state()
	{
		$id = $this->link->get_segment('id');
		$state = $this->link->get_segment('state');
		$field = $this->link->get_segment('field');

		$model = array(
			'from' => 'apply',
			'conditions' => array('where'=>array('id'=>$id)),
		);

		if($field == 'state') 
		{
			$model['set'] = array('state' => $state);
		}
		else if($field == 'result') 
		{
			$model['set'] = array('result' => $state);
		}
		else if($field == 'ismodify')
		{
			$model['set'] = array('ismodify' => $state);
		}
		
		$this->save_row($model);

		redirect($this->link->get(array('action'=>'index', 'id'=>NULL, 'field'=> NULL, 'state'=>NULL)));
	}

	// --------------------------------------------------------------------
	
	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function change_result()
	{
		$id = $this->link->get_segment('id');
		$result = $this->input->post('result');
		
		$model = array(
			'from' => 'apply',
			'conditions' => array('where' => array('id' => $id)),
			'data' => array('result' => $result),
			'include_company' => FALSE
		);
		$this->save_row($model);

		echo 'ok';
	}

	// --------------------------------------------------------------------
	
	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function get_json_data()
	{
		$category = $this->input->post('category');
		
		$model = array(
			'select' => array(
				'id, subject',
				array('CASE WHEN (startdate > now() OR enddate < now() OR state = \'2\') THEN \'마감\' ELSE \'진행중\' END AS new_state', FALSE)
			),
			'from' => 'business',
			'conditions' => array('where'=>array('category'=>$category)),
			'include_company' => FALSE,
			'order' => array('new_state' => 'DESC', 'id' => 'DESC')
		);
		$data = $this->get_entries($model);

		echo json_encode($data['data']);

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
	public function get_my_json_data()
	{
		$category = $this->input->post('category');
		$ismember = $this->input->post('ismember');
		
		$model = array(
			'select' => array(
				'A.id as apply_id',
				'B.id,B.subject',
				array('CASE WHEN (B.startdate > now() OR B.enddate < now() OR B.state = \'2\') THEN \'마감\' ELSE \'진행중\' END AS new_state', FALSE)
			),
			'from' => 'apply A',
			'join' => array(
				array('business B', 'A.pid=B.id'),
			),
			'conditions' => array(
				'where' => array('ismember'=> $ismember, 'category' => $category, 'A.state >' => 1)
			),
			'order' => array('new_state' => 'DESC', 'id' => 'DESC')
		);
		$data = $this->get_entries($model);

		echo json_encode($data['data']);

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
	public function member_search()
	{
		$this->title = "회원 검색";
		$text = get_field('sh_text');
		$text = $this->db->escape_like_str($text);

		$data = array();
		$data['sh_text'] = $text;

		if(!empty($text)) {
			$model = array(
				'select' => 'A.id, A.name, A.created, A.userid, B.compname',
				'from' => 'member A',
				'join' => array('company_info B', 'A.id=B.ismember', 'left'),
				'conditions' => array('where' => array("A.name like '%" . $text . "%'")),
				'include_search' => TRUE,
				'limit' => 5,
				'paging' => TRUE,
				'include_company' => FALSE
			);
			$data = $this->get_entries($model);
		}

		$view = array(
			'skin' => 'popup/member_pop',
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
	public function get_member_data()
	{
		$ismember = $this->input->post('id');

		$model= array(
			'select' => array(
				'A.id as ismember, A.name as chief_name, A.userid',
				'C.title',
				'B.id as bid, B.compname, B.comp_num, B.found_day, B.corporate_num, B.comp_status1, B.comp_status2, B.comp_status3, B.venture_ap, B.venture_date, B.venture_num, B.listed_check, B.listed_day, B.attached_lab',
				'B.attached_addr, B.ceo_name, B.comp_email, B.zipcode, B.address1, B.address2, B.address_status, B.phone, B.phone1, B.phone2, B.phone3, B.fax, B.fax1, B.fax2, B.fax3',
				'B.business_status, B.business_type, B.content, B.homepage',
			),
			'from' => 'member A',
			'join' => array(
				array('company_info B', 'A.id=B.ismember', 'left'),
				array('member_group C', 'A.group=C.id'),
			),
			'conditions' => array('where' => array('A.id' => $ismember)),
			'include_company' => FALSE,
			'not_exists' => '_error_exists'
		);
		$data = $this->get_row($model);
		
		echo json_encode($data['data']);
		
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
	protected function _process_apply_view_entries(&$item, $data)
	{
		if( ! empty($data['files'][$item['id']]))
		{
			foreach($data['files'][$item['id']] as $file) 
			{
				if($file['target'] == 'add_file') 
				{
					$item['add_files'][$file['target']][] = $file;
				}
				else
				{
					//unset($item['com_files']);
					$item['com_files'][$file['target']][] = $file;
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
	protected function _process_apply_write_entries(&$item, $data)
	{
		$item['com_files'] = array();
		$item['add_files'] = array();
		$target_arr = array('com_file1', 'com_file2', 'com_file3', 'com_file4', 'add_file');
		
		if( ! empty($data['files'][$item['id']]))
		{
			foreach($data['files'][$item['id']] as $file)
			{
				if($file['target'] == 'add_file') 
				{
					$item['add_files'][$file['target']][] = $file;
				}
				else
				{
					if(in_array($file['target'], $target_arr))
					{
						unset($item['com_files']);
						$item['com_files'][$file['target']][] = $file;
					}
				}
				
				$view = array(
					'skin' => 'common/attachment',
					'layaout' => '',
					'data' => array('files' => ($file['target'] == 'add_file' ? $item['add_files'][$file['target']] : $item['com_files'][$file['target']])),
					'return' => TRUE
				);
				$item['fileList'][$file['target']] = $this->show($view);
			}
		}
	}
    
  // --------------------------------------------------------------------

  /**
   * Initialize the Controller Preferences
   *
   * @access    private
   * @params array
   * @return    void
   */
  public function excel()
  {
    $model = array(
        'select' => array(
            'A.*',
            'B.compname, B.comp_num, B.ceo_name',
            'B.business_status, B.business_type, B.content, B.homepage',
            'C.subject',
            'D.title as category_title'
        ),
        'from' => 'apply A',
        'join' => array(
            array('apply_company_info B', 'A.id=B.apply_id'),
            array('business C', 'A.pid=C.id'),
            array('business_category D', 'A.category=D.id'),
        ),
        'conditions' => array(
            'where' => array('A.state >' => 1)
        ),
        //'limit' => 10,
        //'paging' => TRUE,
        //'paging_action' => 'index',
        'order' => array('A.id'=>'DESC'),
        'include_file' => TRUE
    );
    $data = $this->get_entries($model);
    
    $search_category = get_field('search_category'); 
    $search_category = $this->db->escape_like_str($search_category);
    
    if(!empty($search_category) && $search_category != 'all')
    {
        $model['conditions']['where']['A.category'] = $search_category;
    }
    
    $search_field = get_field('search_field');
    $search_field = $this->db->escape_like_str($search_field);
    $search_keyword = get_field('search_keyword');
    $search_keyword = $this->db->escape_like_str($search_keyword);

    if(!empty($search_field) && !empty($search_keyword)) {
        if($search_field == 'all')
        {
            $model['conditions']['where'][] = array('(C.subject like \'%'.trim($search_keyword).'%\' OR A.chief_name like \'%'.trim($search_keyword).'%\' OR B.compname like \'%'.trim($search_keyword).'%\')');
        }
        else
        { 
            switch($search_field)
            {
                case 'subject' :
                    $search_field = 'C.subject';
                    break;
                case 'name' :
                    $search_field = 'A.chief_name';
                    break;
                case 'compname' :
                    $search_field = 'B.compname';
                    break;
                default :
                    $search_field = 'C.subject';
            }
            $model['conditions']['where'][] = array($search_field.' like \'%'.trim($search_keyword).'%\'');
        }
    }
    
    $search_subject = get_field('search_subject');
    $search_subject = $this->db->escape_like_str($search_subject);
    
    if(!empty($search_subject)) 
    {
        $model['conditions']['where']['A.pid'] = trim($search_subject);
    }
    
    $data['data'] = $this->get_entries($model);
    
    $model = array(
        'from' => 'business_category',
        'order' => array('sort' => 'ASC')
    );
    $data['category'] = $this->get_entries($model);

    $data['title'] = "접수관리";

    $this->output->enable_profiler(FALSE);
    $this->output->set_header('Content-Type: application/vnd.ms-excel;charset=UTF-8');
    $this->output->set_header('Content-Disposition: attachment; filename='.mb_convert_encoding("접수관리 엑셀파일", 'euc-kr', 'utf-8').'-'.date('Ymd').'.xls');
    $this->output->set_header('Pragma: no-cache');
    $this->output->set_header('Expires: 0');

    $view = array(
      'skin' => 'manager/excel',
      'data' => $data
    );
    $this->show($view);

    unset($this->load->_ci_view_path);
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
		script('back', '게시물이 존재하지 않습니다.');
	}
	
	// --------------------------------------------------------------------	
	
	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	private function _exception($log)
	{
		log_message('error', $log);
		die(sprintf('{"jsonrpc" : "2.0", "error" : {"code": 500, "message": "%s"}, "id" : "id"}', strip_tags($log)));
	}
	
	// --------------------------------------------------------------------
}
