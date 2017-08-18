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
 * 회원접근 클래스 입니다.
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Accounts extends PL_Controller {

    /**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
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
        $data['userid'] = '';
        $data['check_remember'] = FALSE;
        if(get_cookie('main_remember_id'))
        {
            $data['userid'] = get_cookie('main_remember_id');
            $data['check_remember'] = TRUE;
        }
        
		$data['redirect'] = $this->link->get_segment('redirect', FALSE);
		 
        $view = array(
            'skin' => 'accounts/main_login',
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
	public function admin_login()
	{
		$data['redirect'] = $this->link->get_segment('redirect', FALSE);
		$data['action'] = $this->router->fetch_method();

		if($this->account->is_logged() !== FALSE)
		{
			if( ! empty($data['redirect']))
			{
				script('redirect', '이미 로그인 되어있습니다.', decode_url($data['redirect']));
			}
			else
			{
				script('back', '이미 로그인 되어있습니다.');
			}
		}

		$data['userid'] = '';
		$data['check_remember'] = FALSE;
		if(get_cookie('admin_remember_id'))
		{
			$data['userid'] = get_cookie('admin_remember_id');
			$data['check_remember'] = TRUE;
		}

		$view = array(
			'skin' => 'accounts/admin_login',
			'layout' => FALSE,
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
	public function do_login()
	{
		$action = $this->input->post('action');

		$this->form_validation->set_rules('userid', '아이디', 'trim|required');
		$this->form_validation->set_rules('passwd', '비밀번호', 'trim|required');
		if ($this->form_validation->run() === FALSE)
		{
			if(empty($action))
			{
				script('back', '잘못된 접근 입니다.');
			}
			else
			{
				$this->$action();
			}
			return FALSE;
		}

		$userid = $this->input->post('userid');
		$passwd = $this->input->post('passwd');
		$redirect = decode_url($this->input->post('redirect'));

		$model = array(
			'from' => 'member',
			'conditions' => array('where' => array('userid' => $userid, 'deleted is NULL')),
			'not_exists' => '_error_exists'
		);
		$row = $this->get_row($model);

		if($row['data']['passwd'] != sha512($passwd))
		{
			$this->_error_exists();
		}

		$model = array(
			'from' => 'member',
			'conditions' => array('where' => array('id' => $row['data']['id'])),
			'data' => array('last_login' => date('Y-m-d H:i:s'))
		);
		$this->save_row($model);

		$this->session->set_userdata('logged', $row['data']['id']);

		 if($this->input->post('remember_id'))
		 {
			 set_cookie('admin_remember_id', $userid, 999999);
		 }
		else
		{
			delete_cookie('admin_remember_id');
		}

		if( ! empty($redirect))
		{
			redirect($redirect);
		}
		else
		{
			redirect(sprintf('/%s', $this->menu->current['map']));
		}
	}


    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    public function main_do_login()
    {
        $action = $this->input->post('action');

        $this->form_validation->set_rules('userid', '아이디', 'trim|required');
        $this->form_validation->set_rules('passwd', '비밀번호', 'trim|required');

        if($this->form_validation->run() === FALSE)
        {
            if(empty($action))
            {
                script('back', '잘못된 접근 입니다.');
            }
            else
            {
                $this->$action();
            }

            return FALSE;
        }

        $userid = $this->input->post('userid');
        $passwd = $this->input->post('passwd');

        $redirect = decode_url($this->input->post('redirect'));
        $key_url = decode_url($this->input->post('key_url'));
        $autologin = $this->input->post('autologin');
		
    // 회원정보 불러오기
        $model = array(
            'select' => 'id, userid, passwd, is_accredit, name, created, accredit, last_login, group',
            'from' => 'member',
            'conditions' => array('where' => array('userid' => $userid)),
            'not_exists' => '_error_exists3',
            'include_company' => FALSE
        );
        $row = $this->get_row($model);

    // 패스워드 체크
        if($row['data']['passwd'] != sha512($passwd))
        {
            $this->_error_exists3();
        }
    /*
        if($row['data']['passwd'] != sha512($passwd))
        {
            $action = $this->input->post('action');
            script('redirect', '비밀번호를 확인해 주세요.', $this->link->get(array('action'=>$action, 'userid'=>encode_url($userid))));
            //$this->_error_exists();
        }
     */ 

        // 메일 인증 체크
        $this->_check_activation($row['data']['id']);

        $first_login = FALSE;
        // 최초로그인인지 확인 최초로그인 시 희망정보로 넘기기
        if($row['data']['last_login'] == $row['data']['created'])
        {
            $first_login = TRUE;
        }

    // 마지막로그인 시간 갱신
        $model = array(
            'from' => 'member',
            'conditions' => array('where' => array('id' => $row['data']['id'])),
            'data' => array('last_login' => date('Y-m-d H:i:s')),
            'include_time' => FALSE,
            'include_company' => FALSE
        );
        $this->save_row($model);

    // 세션 및 쿠키등록
        $this->session->set_userdata('logged', $row['data']['id']);

        // 마지막 접속 아이디 기록
        set_cookie('latest_userid', $row['data']['userid'], 999999);

    // 자동 로그인
        if($autologin == 'Y')
        {
            set_cookie('autologin_id', $row['data']['id'], 999999);
            set_cookie('autologin_auth', sha512($row['data']['id'].$row['data']['passwd']), 999999);
        }
        
    // 아이디 저장
        if($this->input->post('remember_id'))
        {
            set_cookie('main_remember_id', $userid, 999999);
        }
        else
        {
            delete_cookie('main_remember_id');
        }

        if( ! empty($redirect))
        {
            redirect($redirect);
        }
        else
        {
            redirect(sprintf('/%s', $this->menu->current['map']));
        }
    }

    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */

    public function accredit_check()
    {
        //$this->html['css'][] = '/_res/css/docs_emailCertify.css';
        
        $userid = decode_url($this->link->get_segment('email'));
        $accredit = $this->link->get_segment('accredit');
        
        $data['is_accredit'] = 'Y';
        // 회원정보 불러오기
        $model = array(
            'from' => 'member',
            'conditions' => array('where' => array('userid' => $userid, 'accredit' => $accredit)),
            'data' => $data,
        );
        $data = $this->save_row($model);
        $row = $this->get_row($model);

        if( !empty($row['data']) )
        {
            $view = array(
                'skin' => 'accounts/email_certify01',
                'layout' => FALSE,
                'data' => $row
            );
        } else {
            $view = array(
                'skin' => 'accounts/email_certify03',
                'layout' => FALSE,
                'data' => $row
            );
        }
        $this->show($view);
    }
    
    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    public function accredit()
    {
        $userid = decode_url($this->link->get_segment('userid', FALSE));
        $first = $this->link->get_segment('first', FALSE);

        // 회원정보 불러오기
        $model = array(
            'select' => 'id, userid, passwd, is_accredit, name',
            'from' => 'member',
            'conditions' => array('where' => array('userid' => $userid)),
            'not_exists' => '_error_exists',
            'include_company' => FALSE
        );
        $row = $this->get_row($model);

        $data['name'] = $row['data']['name'];

        $userid = $row['data']['userid'];
        $data['userid'] = $userid;

        $urlstart = strpos($userid, '@');
        $url = substr($userid, $urlstart);
        $urldomain = '';

        switch($url)
        {
            case '@gmail.com' :
                $urldomain = 'http://gmail.com';
                break;
            case '@nate.com' :
                $urldomain = 'http://home.mail.nate.com/login/login.html?s=mail&redirect=http%3A%2F%2Fmail3.nate.com%2F';
                break;
            case '@naver.com' :
                $urldomain = 'http://mail.naver.com/login';
                break;
            case '@daum.net' :
            case '@hanmail.net' :
                $urldomain = 'http://mail.daum.net/hanmailex/Goto.daum';
                break;
            case '@kaist.ac.kr' :
                $urldomain = 'http://mail.kaist.ac.kr/SensMail?act=LOGINFORM';
                break;
            case '@plani.co.kr' :
                $urldomain = 'http://mail.plani.co.kr';
                break;
        }

        $data['url'] = $urldomain;

        $data['first'] = $first;

        $this->html['css'][] = '/_res/css/docs_emailCertify.css';

        $view = array(
            'skin' => 'accounts/email_certify02',
            'layout' => FALSE,
            'data' => $row
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
	public function logout()
	{
		$this->session->sess_destroy();

		$redirect = decode_url($this->link->get_segment('redirect', FALSE));

		if( ! empty($redirect))
		{
			redirect($redirect);
		}
		else
		{
			redirect(sprintf('/%s', $this->menu->current['map']));
		}
	}
    
    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    public function join()
    {
        $view = array(
            'skin' => 'accounts/join_policy'
        );
        $this->show($view);
    }
    
    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    public function join_form()
    {
        $view = array(
            'skin' => 'accounts/join_form'
        );
        $this->show($view);
    }
    
    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    public function join_view()
    {
        
        $id = $this->link->get_segment('id');
        
        $model = array(
            'from' => 'member',
            'conditions' => array('where' => array('id' => $id))
        );
        $data = $this->get_row($model);

        $view = array(
            'skin' => 'accounts/join_complete',
            'data' => $data
        );
        $this->show($view);
    }

	// --------------------------------------------------------------------
    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    public function join_save()
    {

        $id = $this->account->get('id');

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

        if( empty($id)) {
            $data['group'] = '14';
        }
        $data['department'] = $this->input->post('department');
        $data['name'] = $this->input->post('name');
        $data['email'] = $this->input->post('userid');
        
        $this->load->helper('string');
        $accredit = random_string('alnum', 4);
        $data['accredit'] = $accredit;
        
        if($this->input->post('passwd'))
        {
            //$data['passwd'] = $this->encrypt->encode($this->input->post('passwd'));
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
        
        if( empty($id))
        {
            
            $view = array(
                'skin' => 'accounts/email_mailling',
                'layout' => FALSE,
                'data' => $data,
                'return' => TRUE
            );
            $body = $this->show($view);

            // 메일 발송
            $this->load->library('Jmail');

            // 필수
            $option['to'] = $this->input->post('userid');
            $option['toname'] = $this->input->post('userid');
            $option['subject'] = '대전정보문화산업진흥원 인증메일 입니다.';
            $option['content'] = $body;


            $this->jmail->send($option);
        } 
        
        unset($data);
        
        // 회원가입 시 기업정보 자동 등록
        if( empty($id))
        {
            $ismember = $this->input->post('ismember');
            
            $data['ismember']        = $affect_id;
            $data['comp_num'] = $this->input->post('comp_num1').'-'.$this->input->post('comp_num2').'-'.$this->input->post('comp_num3');
            $data['corporate_num'] = $this->input->post('corporate_num1').'-'.$this->input->post('corporate_num2').'-'.$this->input->post('corporate_num3');
            $data['venture_num'] = $this->input->post('venture_num1').'-'.$this->input->post('venture_num2').'-'.$this->input->post('venture_num3');
            
            // 기본정보 입력
            $model = array(
                'from' => 'company_info',
                'conditions' => ( ! empty($ismember)) ? array('where'=>array('ismember'=>$ismember)) : NULL,
                'data' => $data,
                'include_file' => TRUE,
                'include_dynamic' => TRUE
            );
            $this->save_row($model);
        }                   
        
        if( ! empty($id))
        {
            redirect(sprintf('/%s', $this->menu->current['map']));
        } else {
            redirect($this->link->get(array('action'=>'join_view', 'id'=>$affect_id)));
        }
    }

    // --------------------------------------------------------------------
    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    public function joinUpdate()
    {
        
        $id = $this->account->get('id');

        $model= array(
            'from' => 'member',
            'conditions' => array('where' => array('id' => $id)),
            'include_file' => TRUE,
            'include_dynamic' => TRUE,
            'callback' => '_process_entries',
            'not_exists' => '_error_exists2'
        );
        $data = $this->get_row($model);

        $view = array(
            'skin' => 'accounts/info_modify',
            'data' => $data
        );
        $this->show($view);
    }

    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    public function infoCorp()
    {
        $id = $this->account->get('id');
        
        if( ! empty($id))
        {
            $model= array(
                'select' => array(
                    'A.*',
                    'B.id as bid, B.compname, B.comp_num, B.found_day, B.corporate_num, B.comp_status1, B.comp_status2, B.comp_status3, B.venture_ap, B.venture_date, B.venture_num, B.listed_check, B.listed_day, B.attached_lab,
                     B.attached_addr, B.ceo_name, B.comp_email, B.zipcode, B.address1, B.address2, B.address_status, B.phone, B.phone1, B.phone2, B.phone3, B.fax, B.fax1, B.fax2, B.fax3,
                     B.business_status, B.business_type, B.content, B.homepage',
                ),
                'from' => 'member A',
                'join' => array('company_info B', 'A.id=B.ismember', 'left'),
                'conditions' => array('where' => array('id' => $id)),
                'include_file' => 'ALL',
                'include_dynamic' => TRUE,
                'not_exists' => '_error_exists2'
            );
            $data = $this->get_row($model);

            $data['data']['passwd'] = NULL;

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
             
            $data = $this->set_default(array('member', 'company_info'));
            $data['data'] = array_merge($data['data'], array('comp_num1'=>'','comp_num2'=>'','comp_num3'=>'','corporate_num1'=>'','corporate_num2'=>'','corporate_num3'=>'', 'venture_num1'=>'', 'venture_num2'=>'', 'venture_num3'=>''));
            $data['data']['join_date'] = date('Y-m-d');
            $data['data']['is_work'] = 'Y';
            $this->_error_exists2();
        }

        $model = array(
            'from' => 'member_group',
            'order' => array('sort' => 'DESC')
        );
        $data['group'] = $this->get_entries($model);

        $view = array(
            'skin' => 'accounts/info_Corp',
            'data' => $data
        );
        $this->show($view);
    }

    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    public function infoCorp_save()
    {

        $id = $this->account->get('id');
        
        $data['compname']        = $this->input->post('compname');
        //$data['comp_num'] = $this->input->post('comp_num1').(!empty($this->input->post('comp_num2')) ? '-'.$this->input->post('comp_num2') : '').(!empty($this->input->post('comp_num3')) ? '-'.$this->input->post('comp_num3') : '');
        $data['comp_num'] = $this->input->post('comp_num1').'-'.$this->input->post('comp_num2').'-'.$this->input->post('comp_num3');
        $data['found_day']       = $this->input->post('found_day');
        //$data['corporate_num'] = $this->input->post('corporate_num1').(!empty($this->input->post('corporate_num2')) ? '-'.$this->input->post('corporate_num2') : '').(!empty($this->input->post('corporate_num3')) ? '-'.$this->input->post('corporate_num3') : '');
        $data['corporate_num']   = $this->input->post('corporate_num1').'-'.$this->input->post('corporate_num2').'-'.$this->input->post('corporate_num3');
        $data['comp_status1']     = $this->input->post('comp_status1');
        $data['comp_status2']     = $this->input->post('comp_status2');
        $data['comp_status3']     = $this->input->post('comp_status3');
        $data['venture_ap']      = $this->input->post('venture_ap');
        $data['venture_date']    = $this->input->post('venture_date');
        //$data['venture_num'] = $this->input->post('venture_num1').(!empty($this->input->post('venture_num2')) ? '-'.$this->input->post('venture_num2') : '').(!empty($this->input->post('venture_num3')) ? '-'.$this->input->post('venture_num3') : '');
        $data['venture_num'] = $this->input->post('venture_num1').'-'.$this->input->post('venture_num2').'-'.$this->input->post('venture_num3');
        $data['listed_check']    = $this->input->post('listed_check');
        $data['listed_day']      = $this->input->post('listed_day');
        $data['attached_lab']    = $this->input->post('attached_lab');
        $data['attached_addr']   = $this->input->post('attached_addr');
        $data['ceo_name']        = $this->input->post('ceo_name');
        $data['comp_email']      = $this->input->post('comp_email');
        $data['zipcode']         = $this->input->post('zipcode');
        $data['address1']        = $this->input->post('address1');
        $data['address2']        = $this->input->post('address2');
        $data['address_status']        = $this->input->post('address_status');
        $data['phone1']          = $this->input->post('phone1');
        $data['phone2']          = $this->input->post('phone2');
        $data['phone3']          = $this->input->post('phone3');
        $data['phone']           = $this->input->post('phone1').'-'.$this->input->post('phone2').'-'.$this->input->post('phone3');
        $data['fax1']            = $this->input->post('fax1');
        $data['fax2']            = $this->input->post('fax2');
        $data['fax3']            = $this->input->post('fax3');
        $data['fax']             = $this->input->post('fax1').'-'.$this->input->post('fax2').'-'.$this->input->post('fax3');
        $data['business_status'] = $this->input->post('business_status');
        $data['business_type']   = $this->input->post('business_type');
        $data['content']         = $this->input->post('content');
        $data['homepage']        = $this->input->post('homepage');
        
        $model = array(
            'from' => 'company_info',
            //'conditions' => ( ! empty($ismember)) ? array('where'=>array('ismember'=>$ismember)) : NULL,
            'conditions' => array('where' => array('ismember' => $id)),
            'data' => $data,
            'include_file' => TRUE,
            'include_dynamic' => TRUE
        );

        $this->save_row($model);

        redirect($this->link->get(array('action'=>'infoCorp')));
    }

    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    private function _check_activation($member_id, $first=FALSE)
    {
        $model = array(
            'from' => 'member',
            'conditions' => array('where' => array('id' => $member_id)),
            'not_exists' => '_error_exists',
            'include_company' => FALSE
        );
        $data = $this->get_row($model);

        if($data['data']['is_accredit'] == 'N')
        {
            
            $this->load->helper('string');
            $accredit = random_string('alnum', 4);
            $data['accredit'] = $accredit;
            
            $model = array(
                'from' => 'member',
                'conditions' => array('where' => array('id' => $data['data']['id'])),
                'data' => array('accredit' => $accredit),
                'include_time' => FALSE,
                'include_company' => FALSE
            );
            $this->save_row($model);
            $data1 = $this->get_row($model);

            $view = array(
                'skin' => 'accounts/email_mailling2',
                'layout' => FALSE,
                'data' => $data1,
                'return' => TRUE
            );
            $body = $this->show($view);

            // 메일 발송
            $this->load->library('Jmail');

            // 필수
            $option['to'] = $this->input->post('userid');
            $option['toname'] = $this->input->post('userid');
            $option['subject'] = '대전정보문화산업진흥원 인증메일 입니다.';
            $option['content'] = $body;


            $this->jmail->send($option);
            
            redirect($this->link->get(array('action' => 'accredit', 'userid' => encode_url($data['data']['userid']) , 'first' => $first), TRUE));
            
        }
    }

    // --------------------------------------------------------------------
    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    public function find_pwd()
    {

        $id = $this->input->post('userid');
        
        if ( empty($id)) {
            $this->_error_exists2();
        }
        
        // 임시비밀번호 업데이트
        $this->load->helper('string');
        $passwd1 = random_string('alnum', 4);
        $passwd2 = rand(100,999);
        
        $c_passwd = $passwd1.$passwd2;

        $model = array(
            'from' => 'member',
            'conditions' => array('where' => array('userid' => $id)),
            'data' => array('passwd' => sha512($c_passwd)),
            'include_file' => TRUE,
            'include_dynamic' => TRUE
        );
        $data = $this->save_row($model);
        
        // 임시비밀번호 이메일 전송
        $data1 = $this->get_row($model);
        $data1['data']['passwd'] = $c_passwd;
            
        $view = array(
            'skin' => 'accounts/findPwd_mailling',
            'layout' => FALSE,
            'data' => $data1,
            'return' => TRUE
        );
        $body = $this->show($view);

        // 메일 발송
        $this->load->library('Jmail');

        // 필수
        $option['to'] = $this->input->post('userid');
        $option['toname'] = $this->input->post('userid');
        $option['subject'] = '대전정보문화산업진흥원 임시비밀번호 입니다.';
        $option['content'] = $body;


        $this->jmail->send($option);
        
        redirect($this->link->get(array('action'=>'index')));
        
    }

    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
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
            echo '이미 등록된 이메일이 있습니다.';
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
     * @access  private
     * @params array
     * @return  void
     */
    public function companyNum_check()
    {
        $comp_num = $this->input->post('comp_num');
        
        $model = array(
            'select' => 'id',
            'from' => 'company_info',
            'conditions' => array('where'=>array('comp_num'=>$comp_num))
        );
        
        if($this->check_exists($model) == TRUE)
        {
            echo '이미 등록된 사업자등록번호가 있습니다.';
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
	public function myTransfer()
	{
        $id = $this->account->get('id');
        $userid = $this->account->get('userid');
        
        $model= array(
            'from' => 'member',
            'conditions' => array('where' => array('id' => $id)),
            'include_file' => TRUE,
            'include_dynamic' => TRUE,
            'callback' => '_process_entries',
            'not_exists' => '_error_exists2'
        );
        $data = $this->get_row($model);
        
        // aropa 디비 약관 불러오기
        $this->_set_database('aropa');
        
        // 이관 유무 체크
        $model= array(
            'from' => 'member',
            'conditions' => array('where' => array('userid' => $userid)),
        );
        $data_check = $this->get_row($model);
        
        // 약관 불러오기
        $model = array(
            'from' => 'terms',
            'conditions' => array('where'=>array('bridge_id'=>'2797695'))
        );
        
        $data = $this->get_entries($model);
        
        $this->_set_database();
        
        // 정보이관이 이미 되어있다면
        if( !empty($data_check['data']['created']) )
        {
            $view = array(
                'skin' => 'accounts/cm_transfer',
                'data' => $data_check
            );
        } else {
            $view = array(
                'skin' => 'accounts/cm_transfer',
                'data' => $data
            );
        }
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
	public function tf_control()
	{
        $id = $this->account->get('id');
        
        $model= array(
            'from' => 'member',
            'conditions' => array('where' => array('id' => $id)),
            'not_exists' => '_error_exists2'
        );
        $data = $this->get_row($model);
        
        $model2= array(
            'from' => 'company_info',
            'conditions' => array('where' => array('ismember' => $id)),
        );
        $data2 = $this->get_row($model2);
        
        // cms member 테이블에 이관 날짜 기록
        $data3['data_transfer'] =  date("Y-m-d");
        
        $model4 = array(
            'from' => 'member',
            'conditions' => ( ! empty($id)) ? array('where'=>array('id'=>$id)) : NULL,
            'data' => $data3,
        );
        $this->save_row($model4);
        //-----------------------------------------------------------------------------
        
        $this->_set_database('aropa');
        
        $model= array(
            'from' => 'member',
            'conditions' => array('where' => array('id' => $id)),
            'not_exists' => '_error_exists2'
        );
        
        // 개인정보 이관
        $transfer['bridge_id'] = '2797695';
        $transfer['group'] = '665362';
        $transfer['name'] = $data['data']['name'];
        $transfer['userid'] = $data['data']['userid'];
        $transfer['passwd'] = $data['data']['passwd'];
        $transfer['is_accredit'] = 'Y';

        $model = array(
            'from' => 'member',
            //'conditions' => ( ! empty($id)) ? array('where'=>array('id'=>$id)) : NULL,
            'data' => $transfer,
            'include_file' => TRUE,
            'include_dynamic' => TRUE
        );

        $affect_id = $this->save_row($model);

        // 기업정보 이관        
        $transfer_corp['ismember'] = $affect_id;
        $transfer_corp['ci'] = $data2['data']['ci'];
        $transfer_corp['agree'] = 'Y';
        $transfer_corp['compname'] = $data2['data']['compname'];
        $transfer_corp['content'] = $data2['data']['content'];
        $transfer_corp['ceo_name'] = $data2['data']['ceo_name'];
        $transfer_corp['found_day'] = $data2['data']['found_day'];
        $transfer_corp['comp_num'] = $data2['data']['comp_num'];
        $transfer_corp['corporate_num'] = $data2['data']['corporate_num'];
        $transfer_corp['business_status'] = $data2['data']['business_status'];
        $transfer_corp['business_type'] = $data2['data']['business_type'];
        $transfer_corp['address1'] = $data2['data']['address1'];
        $transfer_corp['address2'] = $data2['data']['address2'];
        $transfer_corp['phone'] = $data2['data']['phone'];
        $transfer_corp['phone1'] = $data2['data']['phone1'];
        $transfer_corp['phone2'] = $data2['data']['phone2'];
        $transfer_corp['phone3'] = $data2['data']['phone3'];
        $transfer_corp['fax'] = $data2['data']['fax'];
        $transfer_corp['fax1'] = $data2['data']['fax1'];
        $transfer_corp['fax2'] = $data2['data']['fax2'];
        $transfer_corp['fax3'] = $data2['data']['fax3'];
        $transfer_corp['email'] = $data2['data']['comp_email'];
        $transfer_corp['homepage'] = $data2['data']['homepage'];
       
        $model3 = array(
            'from' => 'cm_value_bagic',
            //'conditions' => ( ! empty($id)) ? array('where'=>array('id'=>$id)) : NULL,
            'data' => $transfer_corp,
            'include_file' => TRUE,
            'include_dynamic' => TRUE
        );
        
        $this->save_row($model3);
        
        $this->_set_database();
        
        redirect($this->link->get(array('action'=>'myTransfer')));
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
		$action = $this->input->post('action');
		script('redirect', '아이디 또는 비밀번호를 확인해 주세요.', $this->link->get(array('action'=>$action)));
	}

    // --------------------------------------------------------------------
	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	protected function _error_exists2()
	{
		script('redirect', '로그인을 해주시기 바랍니다.', $this->link->get(array('action'=>'index')));
	}
    
    
    // --------------------------------------------------------------------
    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    protected function _error_exists3()
    {
        script('redirect', '아이디 또는 비밀번호를 확인해 주세요.', $this->link->get(array('action'=>'index')));
    }

}
