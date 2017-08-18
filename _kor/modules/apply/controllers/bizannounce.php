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
class Bizannounce extends PL_Controller {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('file');
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
		$search_page = get_field('search_page');
		$search_page = (int)$search_page;
		if(empty($search_page)) $search_page = 10;
		if(abs($search_page) > 100) $search_page = 100;
		
		$model = array(
			'select' => array(
				'A.*', 
				'B.title as category_title',
				array(
					'CASE WHEN (startdate > now()) THEN \'3\'
						  WHEN (enddate < now() OR state = \'2\') THEN \'2\' 
						  ELSE \'1\'
					 END AS new_state', FALSE
				),
			),
			'from' => 'business A',
			'join' => array('business_category B', 'A.category=B.id', 'left'),
			'conditions' => array('where' => array('A.isnotice' => 'Y')),
			'order' => array('A.id'=>'DESC'),
			'include_file' => TRUE
		);
		$data['notices'] = $this->get_entries($model);

		$model = array(
			'select' => array(
				'A.*', 
				'B.title as category_title',
				array(
					'CASE WHEN (startdate > now()) THEN \'3\'
						  WHEN (enddate < now() OR state = \'2\') THEN \'2\' 
						  ELSE \'1\'
					 END AS new_state', FALSE),
			),
			'from' => 'business A',
			'join' => array('business_category B', 'A.category=B.id', 'left'),
			'conditions' => array('where' => array('A.isnotice' => 'N')),
			'limit' => $search_page,
			'paging' => TRUE,
			'paging_action' => 'index',
			'order' => array('A.id'=>'DESC'),
			'include_file' => TRUE
		);
		
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
				$model['conditions']['where'][] = array('(A.subject like \'%'.trim($search_keyword).'%\' OR A.contents like \'%'.trim($search_keyword).'%\' OR A.name like \'%'.trim($search_keyword).'%\')');
			}
			else
			{ 
				switch($search_field)
				{
					case 'subject' :
						$search_field = 'A.subject';
						break;
					case 'contents' :
						$search_field = 'A.contents';
						break;
					case 'name' :
						$search_field = 'A.name';
						break;
					default :
						$search_field = 'C.subject';
				}
				$model['conditions']['where'][] = array($search_field.' like \'%'.trim($search_keyword).'%\'');
			}
		}

		if(!empty($search_keyword)) 
		{
			$model['conditions']['where'][] = array('A.subject like \'%'.trim($search_keyword).'%\'');
		}
		
		$data['artices'] = $this->get_entries($model);
		
		$data['total_cnt'] = count($data['notices']['data']) + count($data['artices']['data']);
		
		$model = array(
			'from' => 'business_category',
			'order' => array('sort' => 'ASC')
		);
		$data['category'] = $this->get_entries($model);
		
		$view = array(
			'skin' => 'bizannounce/index',
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

		$model = array(
			'select' => array(
				'A.*', 
				'B.title as category_title',
				array(
					'CASE WHEN (startdate > now()) THEN \'3\'
						  WHEN (enddate < now() OR state = \'2\') THEN \'2\' 
						  ELSE \'1\'
					 END AS new_state', FALSE
				),
			),
			'from' => 'business A',
			'join' => array('business_category B', 'A.category=B.id', 'left'),
			'conditions' => array('where' => array('A.id' => $id)),
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
			'callback' => '_process_entries',
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
				'from' => 'business',
				'conditions' => array('where' => array('id' => $id)),
				'data' => array('hit' => $data['data']['hit']+1),
				'include_time' => FALSE
			);
			$this->save_row($model);

			$hit[] = $id;
			$this->session->set_userdata('hit', $hit);
		}
		
		$model = array(
			'select' => 'MIN(id) as id',
			'from' => 'business',
			'conditions' => array('where' => array('id > ' => $id)),
			'order' => array('id' => 'DESC')
		);
		$data['next'] = $this->get_row($model);
		
		$model = array(
			'select' => 'MAX(id) as id',
			'from' => 'business',
			'conditions' => array('where' => array('id < ' => $id)),
			'order' => array('id' => 'DESC')
		);
		$data['prev'] = $this->get_row($model);

		$view = array(
			'skin' => 'bizannounce/view',
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
	public function apply()
	{
		$pid = $this->link->get_segment('pid', FALSE);
		$ismember = $this->account->get('id');
		
		if(empty($pid))
		{
			script('redirect', '잘못된 접근 입니다.',$this->link->get(array('action'=>'index', 'pid'=>NULL, 'id'=>NULL)));
			return FALSE;
		} 
		
		$model = array(
			'select' => array(
				'A.id as pid, A.ismember, A.name, A.category, A.subject, A.contents, A.startdate, A.enddate, A.created', 
				'B.title as category_title',
				array(
					'CASE WHEN (startdate > now()) THEN \'3\'
						  WHEN (enddate < now() OR state = \'2\') THEN \'2\' 
						  ELSE \'1\'
					 END AS new_state', FALSE
				),
			),
			'from' => 'business A',
			'join' => array('business_category B', 'A.category=B.id', 'left'),
			'conditions' => array('where' => array('A.id' => $pid)),
			'include_schedule' => TRUE,
			'callback' => '_process_entries',
			'not_exists' => '_error_exists'
		);
		$data = $this->get_row($model);
		
		// 기간 체크
		if((int)$data['data']['new_state'] > 2)
		{
			script('redirect', '아직 접수기간이 아닙니다.\n공고기간을 다시 확인해주세요.', $this->link->get(array('action'=>'index', 'id'=>NULL, 'pid'=>NULL)));
			return FALSE;
		}
		else if((int)$data['data']['new_state'] > 1)
		{
			script('redirect', '해당 사업은 이미 마감되었습니다.', $this->link->get(array('action'=>'index', 'id'=>NULL, 'pid'=>NULL)));
			return FALSE;
		}
		
		// 로그인 체크
		if($this->account->is_logged() == FALSE)
		{
			script('redirect', '로그인 후 이용해주세요. ', $this->link->get(array('prefix'=>'login','controller'=>'accounts','action'=>'index', 'id'=>NULL, 'pid'=>NULL, 'redirect'=>encode_url())));
			return FALSE;
		}
	
		$chief_arr = array('id'=>'', 'chief_name' => $this->account->get('name'),'chief_email'=> $this->account->get('userid'),'chief_phone1'=>'','chief_phone2'=>'','chief_phone3'=>'');
		
		// 사업신청여부 체크
		$model = array(
			'from' => 'apply',
			'conditions' => array('where' => array('ismember' => $ismember, 'pid' => $pid)),
			'include_company' => FALSE
		);
		$row = $this->get_row($model);
		
		// state = 1:임시저장 / 2:접수 / 3:승인
		if(!empty($row['data']))
		{
			if((int)$row['data']['state'] > 1)
			{
				script('redirect', '선택하신 사업공고에 이미 접수하였습니다.', $this->link->get(array('prefix'=>'mystatus','controller'=>NULL,'action'=>NULL, 'id'=>NULL, 'pid'=>NULL),TRUE));
				return FALSE;
			}
			else
			{
				message('작성중인 신청서가 있습니다.');
			}
			
			$chief_arr = array('id' => $row['data']['id'],'chief_name' => $row['data']['chief_name'],'chief_email'=> $row['data']['chief_email'],'chief_phone1' => $row['data']['chief_phone1'],'chief_phone2' => $row['data']['chief_phone2'],'chief_phone3' => $row['data']['chief_phone3']); 
			$model = array(
				'select' => array(
					'A.id', 
					'B.id as bid, B.compname, B.comp_num, B.found_day, B.corporate_num, B.comp_status1, B.comp_status2, B.comp_status3, B.venture_ap, B.venture_date, B.venture_num, B.listed_check, B.listed_day, B.attached_lab',
					'B.attached_addr, B.ceo_name, B.comp_email, B.zipcode, B.address1, B.address2, B.address_status, B.phone, B.phone1, B.phone2, B.phone3, B.fax, B.fax1, B.fax2, B.fax3',
					'B.business_status, B.business_type, B.content, B.homepage',
				), 
				'from' => 'apply A',
				'join' => array('apply_company_info B', 'A.id = B.apply_id'),
				'conditions' => array('where' => array('A.ismember' => $ismember, 'A.pid' => $pid)),
				'callback' => '_process_apply_entries',
				'include_file' => TRUE,
				'include_company' => FALSE
			);
		}
		else 
		{
			$model = array(
				'select' => array(
					'id as bid, compname, comp_num, found_day, corporate_num, comp_status1, comp_status2, comp_status3, venture_ap, venture_date, venture_num, listed_check, listed_day, attached_lab',
					'attached_addr, ceo_name, comp_email, zipcode, address1, address2, address_status, phone, phone1, phone2, phone3, fax, fax1, fax2, fax3',
					'business_status, business_type, content, homepage',
				),
				'from' => 'company_info',
				'conditions' => array('where' => array('ismember' => $ismember)),
				'include_company' => FALSE
			);
		}
		
		if($this->check_exists($model) == FALSE)
		{
			$data['myinfo'] = $this->set_default('company_info');
		}
		else
		{
			$data['myinfo'] = $this->get_row($model);
		}
		
		$data['myinfo']['data'] = array_merge($data['myinfo']['data'], $chief_arr);
		
		$temp = array('corporate_num', 'comp_num', 'venture_num');
		foreach ($temp as $item) 
		{
			if(strpos($data['myinfo']['data'][$item], '-') !== false) 
			{
				$arr = explode('-',$data['myinfo']['data'][$item]);
				for($i=0; $i<3; $i++)
				{
					$data['myinfo']['data'][$item.($i+1)] = (!empty($arr[$i]) ? $arr[$i] : '');
				}
			}
			else
			{
				$data['myinfo']['data'][$item.'1'] = $data['myinfo']['data'][$item];
				$data['myinfo']['data'][$item.'2'] = '';
				$data['myinfo']['data'][$item.'3'] = '';
			}
		}
		
		$this->html['css'][] = '/_res/libs/codemirror/codemirror.css';
		$this->html['js'][] = '/_res/libs/codemirror/codemirror.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/xml/xml.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/javascript/javascript.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/css/css.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/clike/clike.js';
		$this->html['js'][] = '/_res/libs/codemirror/mode/php/php.js';

		$view = array(
			'skin' => 'bizannounce/apply',
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
		$this->form_validation->set_rules('chief_name', '담당자 이름', 'trim|required');
		$this->form_validation->set_rules('chief_phone1', '담당자 연락처', 'trim|numeric|required');
		$this->form_validation->set_rules('chief_phone2', '담당자 연락처', 'trim|numeric|required');
		$this->form_validation->set_rules('chief_phone3', '담당자 연락처', 'trim|numeric|required');
		$this->form_validation->set_rules('chief_email', '담당자 이메일', 'trim|valid_email|required');

		if($this->form_validation->run() === FALSE)
		{
			$this->apply();
			return FALSE;
		}
		
		// 로그인 체크
		if($this->account->is_logged() == FALSE)
		{
			script('redirect', '로그인 후 이용해주세요. ', $this->link->get(array('prefix'=>'login','controller'=>'accounts','action'=>'index', 'id'=>NULL, 'pid'=>NULL)));
			return FALSE;
		}

		$pid = $this->input->post('pid');
		$id = $this->input->post('id');
		$ismember = $this->account->get('id');
		
		//사업공고 정보
		$data['ismember'] = $ismember;
		$data['pid'] = $pid;
		$data['category'] = $this->input->post('category');
		
		// 담당자 정보
		$data['chief_name'] = trim($this->input->post('chief_name'));
		$data['chief_phone1'] = trim($this->input->post('chief_phone1'));
		$data['chief_phone2'] = trim($this->input->post('chief_phone2'));
		$data['chief_phone3'] = trim($this->input->post('chief_phone3'));
		$data['chief_email'] = trim($this->input->post('chief_email'));
		if(!empty($id))
		{
			//$data['modify_count'] = $modify_count+1;
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
		redirect($this->link->get(array('action'=>'preview', 'id'=>NULL, 'pid'=>$pid)));
	}

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function preview()
	{
		$pid = $this->link->get_segment('pid');
		if(empty($pid))
		{
			script('redirect', '잘못된 접근 입니다.',$this->link->get(array('action'=>'index', 'pid'=>NULL, 'id'=>NULL)));
			return FALSE;
		}
		
		// 로그인 체크
		if($this->account->is_logged() == FALSE)
		{
			script('redirect', '로그인 후 이용해주세요. ', $this->link->get(array('prefix'=>'login','controller'=>'accounts','action'=>'index', 'id'=>NULL, 'pid'=>NULL)));
			return FALSE;
		}

		$model = array(
			'select' => array(
				'A.*',
				'B.id as bid, B.compname, B.comp_num, B.found_day, B.corporate_num, B.comp_status1, B.comp_status2, B.comp_status3, B.venture_ap, B.venture_date, B.venture_num, B.listed_check, B.listed_day, B.attached_lab',
				'B.attached_addr, B.ceo_name, B.comp_email, B.zipcode, B.address1, B.address2, B.address_status, B.phone, B.phone1, B.phone2, B.phone3, B.fax, B.fax1, B.fax2, B.fax3',
				'B.business_status, B.business_type, B.content, B.homepage',
				'C.subject, C.startdate, C.enddate',
				'D.title as category_title'
			),
			'from' => 'apply A',
			'join' => array( 
				array('apply_company_info B', 'A.id=B.apply_id'),
				array('business C', 'A.pid=C.id'),
				array('business_category D', 'A.category=D.id'),
			),
			'conditions' => array(
				'where' => array('A.ismember' => $this->account->get('id'), 'A.pid' => $pid)
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
			'callback' => '_process_preview_entries',
			'not_exists' => '_error_exists'
		);
		$data = $this->get_row($model);
		
		// state = 1:임시저장 / 2:접수 / 3:승인
		if($data['data']['state']>1) 
		{
			script('redirect', '접근권한이 없습니다.',$this->link->get(array('action'=>'view', 'id'=>$pid, 'pid'=>NULL)));
			return FALSE;
		}

		$view = array(
			'skin' => 'bizannounce/preview',
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
	public function submit()
	{
		$id = $this->input->post('id');
		if(empty($id))
		{
			script('redirect', '잘못된 접근 입니다.',$this->link->get(array('action'=>'index', 'pid'=>NULL, 'id'=>NULL)));
			return FALSE;
		}
		
		// 로그인 체크
		if($this->account->is_logged() == FALSE)
		{
			script('redirect', '로그인 후 이용해주세요. ', $this->link->get(array('prefix'=>'login','controller'=>'accounts','action'=>'index', 'id'=>NULL, 'pid'=>NULL)));
			return FALSE;
		}
		
		// state = 1:임시저장 / 2:접수 / 3:승인
		$model = array(
			'from' => 'apply',
			'conditions' => array('where'=>array('id'=>$id)),
			'data' => array('state' => '2'),
		);
		$affect_id = $this->save_row($model);
		
		message('최종 접수 되었습니다.');
		redirect($this->link->get(array('action'=>'index', 'id'=>NULL, 'pid'=>NULL)));
	}
	
	// --------------------------------------------------------------------
	
		
	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function dupliacate_check()
	{
		$field = $this->input->post('field');
		$value = $this->input->post('value');

		$model = array(
			'select' => 'id',
			'from' => 'company_info',
			'conditions' => array('where'=>array($field => $value))
		);

		if($this->check_exists($model) == TRUE)
		{
			echo '이미 등록된 번호가 있습니다.';
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
	protected function _process_preview_entries(&$item, $data)
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
	protected function _process_apply_entries(&$item, $data)
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
