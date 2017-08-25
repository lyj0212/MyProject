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

        /*$client_id="ce9c521a17494d5b91e7af685c36492b";
        $client_secret="652e22370337446ea904f83387ee38be";
        $access_token = "5912120473.ce9c521.8ba3747637254bd3b60e1ed4fbf854ea";

        $_source=file_get_contents("https://api.instagram.com/v1/users/self/media/recent?client_id=".$client_id."&access_token=".$access_token."&count=6");
        $_data=json_decode($_source);
        $json=$_data->data;
        debug($json);*/


        //$login_url = 'https://api.instagram.com/oauth/authorize/?client_id=ce9c521a17494d5b91e7af685c36492b&redirect_uri=' . urlencode('http://sdi.localhost') . '&response_type=code&scope=basic';


        /*$instagram = new Instagram(array(
            'apiKey'      => 'ce9c521a17494d5b91e7af685c36492b',
            'apiSecret'   => '652e22370337446ea904f83387ee38be ',
            'apiCallback' => 'http://sdi.localhost'
        ));
        $code = 'd52d21f9455343e682420910433d06f9';
        if (true === isset($code))
        {
            $data = $instagram->getOAuthToken($code);
            print_r($data);

        }
        else
        {
            $loginUrl   = $instagram->getLoginUrl();
            echo "<a class=\"button\" href=\"$loginUrl\">Sign in with Instagram</a>";
        }*/

        $this->html['js'][] = '/_res/js/naverLogin_implicit-1.0.3.js';
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
            'select' => 'id, userid, passwd, name, created, last_login, group',
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

        if($row['data']['passwd'] != sha512($passwd))
        {
            $action = $this->input->post('action');
            script('redirect', '비밀번호를 확인해 주세요.', $this->link->get(array('action'=>$action, 'userid'=>encode_url($userid))));
            //$this->_error_exists();
        }

        // 메일 인증 체크
        //$this->_check_activation($row['data']['id']);

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
     * @access	private
     * @params array
     * @return	void
     */
    public function sns_do_login()
    {
        $sns = $this->input->post('sns');
        $snsid = $this->input->post('snsid');
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $picture = $this->input->post('picture');
        $token = $this->input->post('token');
        $key_url = $this->input->post('key_url');
        $aftermove = decode_url($this->input->post('redirect'));

        if(empty($name))
        {
            $name_arr = explode('@', $email);
            $name = $name_arr[0];
        }

        $data['sns'] = $sns;
        $data['snsid'] = $snsid;
        $data['userid'] = $email;
        $data['token'] = $token;
        $data['last_login'] = date('Y-m-d H:i:s');

        switch($data['sns'])
        {
            case 'facebook' :
                $data['fb_id'] = $data['snsid'];
                $data['fb_serialkey'] = $data['token'];
                break;
        }

        $redirect = $this->link->get(array('action' => 'join'), TRUE);

        if( ! empty($email))
        {
            $model = array(
                'from' => 'member',
                'conditions' => array('where' => array('userid' => $email)),
                'include_company' => FALSE
            );
            if($this->check_exists($model) == TRUE)
            {
                $model['data'] = $data;
                $member_id = $this->save_row($model);

                $model = array(
                    'select' => 'id, passwd, name, userid',
                    'from' => 'member',
                    'conditions' => array('where' => array('id' => $member_id)),
                    'not_exists' => '_error_exists',
                    'include_company' => FALSE
                );
                $member_row = $this->get_row($model);

                $this->session->set_userdata('logged', $member_row['data']['id']);

                // 마지막 접속 아이디 기록
                set_cookie('latest_userid', $member_row['data']['userid'], 999999);

                // 로그인 로그
                //$this->loginlog($member_row['data']['id']);

                if( ! empty($aftermove))
                {
                    $redirect = $aftermove;
                }
                else
                {
                    $redirect = sprintf('/%s', $this->menu->current['map']);
                }
            }
        }

        $data['name'] = $name;
        $data['picture'] = $picture;
        $this->session->set_userdata('snsLogin', $data);

        echo $redirect;
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
        $data = $this->set_default('member');
        $data['data']['picture'] = '';
        //$data['enname'] = "";

        ## SNS로 가입시..
        $snsLogin = $this->session->userdata('snsLogin');
        if( ! empty($snsLogin))
        {
            $data['data']['userid'] = $snsLogin['userid'];
            $data['data']['sns'] = $snsLogin['sns'];
            $data['data']['snsid'] = $snsLogin['snsid'];
            $data['data']['picture'] = $snsLogin['picture'];
            $data['data']['token'] = $snsLogin['token'];
            $data['data']['name'] = $snsLogin['name'];
            if(preg_match('/^[A-Za-z]/', trim($snsLogin['name'])) )
            {
                //$data['enname'] = "true";
            }
        }
        $pass_userid = decode_url($this->link->get_segment('passuserid', FALSE));
        if($pass_userid) {
            $data['data']['userid'] = $pass_userid;
        }

        $view = array(
            'skin' => '/accounts/join',
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
    public function terms()
    {
        $this->title = '이용약관';

        $data['agreement'] =  $this->link->get_segment('agreement', FALSE);
        $data['privacy'] =  $this->link->get_segment('privacy', FALSE);

        $view = array(
            'skin' => 'accounts/terms',
            'data' => $data,
            'layout' => 'popup'
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
        $this->form_validation->set_rules('userid', '이메일', 'trim|valid_email|required');
        $this->form_validation->set_rules('name', '이름', 'trim|required');
        $this->form_validation->set_rules('agree', '이용약관', 'trim|required');

        $data['sns'] = $this->input->post('sns');
        $data['snsid'] = $this->input->post('snsid');
        $data['token'] = $this->input->post('token');

        switch($data['sns'])
        {
            case 'facebook' :
                $data['fb_id'] = $data['snsid'];
                $data['fb_serialkey'] = $data['token'];
                break;
        }

        if(empty($data['sns']) OR empty($data['token']))
        {
            $this->form_validation->set_rules('passwd', '비밀번호', 'trim|required|min_length[4]|max_length[30]');
            $this->form_validation->set_rules('passwd2', '비밀번호', 'trim|required|min_length[4]|max_length[30]');
        }

        if($this->form_validation->run() === FALSE)
        {
            $this->join();
            return FALSE;
        }

        $data['userid'] = $this->input->post('userid');
        $data['name'] = $this->input->post('name');

        $model = array(
            'from' => 'member',
            'conditions' => array('where' => array('userid' => $data['userid'])),
            'include_company' => FALSE
        );
        if($this->check_exists($model) == TRUE)
        {
            message('이미 등록된 이메일 입니다.', 'error');
            $this->join();
            return FALSE;
        }

        if($this->input->post('passwd') != $this->input->post('passwd2')){
            message('비밀번호를 다시 입력해주세요.', 'error');
            $this->join();
            return FALSE;
        }
        $data['passwd'] = sha512($this->input->post('passwd'));

        $model = array(
            'select' => 'id',
            'from' => 'member_group',
            'conditions' => array('where' => array('default' => 'Y')),
            'include_company' => FALSE
        );
        $group = $this->get_row($model);

        $data['group'] = $group['data']['id'];
        $data['last_login'] = date('Y-m-d H:i:s');

        // SNS 가입의 경우 인증완료처리.
        if( ! empty($data['sns']) && ! empty($data['token']))
        {
            //$data['is_accredit'] = "Y";
        }
        $model = array(
            'from' => 'member',
            'data' => $data,
            'include_company' => FALSE
        );
        $affect_id = $this->save_row($model);

        ## 소셜 프로필 이미지 업로드
        $picture_url = $this->input->post('picture');
        if( ! empty($picture_url))
        {
            $filedata = file_get_external_contents($picture_url);
            $ext = '.jpg';

            $upload_path = sprintf('attach/files/%s/', date('Ymd'));
            if( ! is_dir($upload_path))
            {
                if( ! @mkdir($upload_path, 0777, TRUE))
                {
                    return FALSE;
                }
            }

            mt_srand();
            $rawname = md5(uniqid(mt_rand()));
            $filename = $rawname.$ext;

            $this->load->helper('file');
            write_file(sprintf('%s%s', $upload_path, $filename), $filedata);

            $picture_data = array(
                'pid' => $affect_id,
                'orig_name' => $filename,
                'raw_name' => $rawname,
                'file_ext' => $ext,
                'file_size' => filesize(sprintf('%s%s', $upload_path, $filename)),
                'upload_path' => $upload_path,
                'target' => 'member_picture',
                'use' => 'Y'
            );

            $model = array(
                'from' => 'files',
                'data' => $picture_data
            );
            $this->save_row($model);
        }

        // 메일 인증 체크
        //$this->_check_activation($affect_id, $first);

        set_cookie('latest_userid', $data['userid']);

        ## 로그인 처리
        $this->session->set_userdata('logged', $affect_id);

        if($this->auth->admin() == TRUE)
        {
            $this->cache->delete('member_super_admin');
        }

        ## 로그인 로그
        //$this->loginlog($affect_id);

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
    public function insta_return()
    {
        $code = $_GET['code'];

        $client_id="ce9c521a17494d5b91e7af685c36492b";
        $client_secret="652e22370337446ea904f83387ee38be";
        $redirect_uri="http://sdi.localhost/order/order_login/accounts/insta_return";

        $url = 'https://api.instagram.com/oauth/access_token';

        $curlPost = 'client_id='. $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = json_decode(curl_exec($ch), true);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($http_code != '200')
            throw new Exception('Error : Failed to receieve access token');

        $access_token=$data['access_token'];
/*
        $_source=file_get_contents("https://api.instagram.com/v1/users/self/media/recent?client_id=".$client_id."&access_token=".$access_token."&count=6");
        $_data=json_decode($_source);
        $json=$_data->data;

        foreach($json as $item) {
            $data['id'] = $item->id;
            $data['name'] = $item->user->full_name;
            $data['picture'] = $item->user->profile_picture;
            $data['token'] = $code;
            $data['access_token'] = $access_token;

            $data['img'] = "<div style=\"float:left;margin:5px;\"><a href=\"".$item->link."\" target=\"_blank\"><img src=\"".$item->images->thumbnail->url."\" class=\"image-style1 respond-img\"></a></div>";
        }*/

        $view = array(
            'skin' => 'accounts/insta_return',
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
    public function naver_return()
    {

        $view = array(
            'skin' => 'accounts/naver_return',
           // 'data' => $data
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
    public function findpass()
    {
        $view = array(
            'skin' => 'accounts/findpass',
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
    public function find_pwd()
    {
        $id = $this->input->post('userid');

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
        $option['toname'] = $this->input->post('userName');
        $option['subject'] = '현대 SDI 임시비밀번호 입니다.';
        $option['content'] = $body;

        if($this->jmail->send($option)){
            echo 'true';
        }
        //redirect($this->link->get(array('action'=>'index')));
        
    }
    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access  private
     * @params array
     * @return  void
     */
    public function is_check()
    {
        $email = $this->input->post('email');
        $name = $this->input->post('name');

        $model = array(
            'select' => 'id, created',
            'from' => 'member',
            'conditions' => array('where'=>array('userid'=>$email, 'name' => $name))
        );
        if($this->check_exists($model) == TRUE)
        {
            $row = $this->get_row($model);
            echo $row['data']['created'];
        }
        else
        {
            echo 'false';
        }
    }

    //--------------------------------------------------------------------

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
