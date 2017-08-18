<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jmail
{
	private $CI;

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
		log_message('debug', "Jmail Class Initialized");

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
	public function send($params)
	{
		$option['from'] = 'info@y-bridge.co.kr';
		$option['fromname'] = $this->CI->account->get('title');
		$option['to'] = '';
		$option['toname'] = '';
		$option['subject'] = '';
		$option['content'] = '';

		$this->CI->_set($option, $params);

		if( ! filter_var($option['to'], FILTER_VALIDATE_EMAIL))
		{
			return FALSE;
		}

		$data['subject'] = $option['subject'];
		$data['qry'] = sprintf('SSV:%s,%s', $option['to'], $option['toname']);
		$data['reject_slist_idx'] = 0;
		$data['block_group_idx'] = 0;
		$data['mailfrom'] = sprintf('"%s" <%s>', $option['fromname'], $option['from']);
		$data['mailto'] = sprintf('"%s" <%s>', $option['toname'], $option['to']);
		$data['replyto'] = sprintf('<%s>', $option['from']);
		$data['errorsto'] = sprintf('<%s>', $option['from']);;
		$data['html'] = 1;
		$data['encoding'] = 0;
		$data['charset'] = 'utf-8';
		$data['sdate'] = date('YmdHis');
		$data['tdate'] = date('YmdHis', strtotime('+10 days'));
		$data['duration_set'] = 1;
		$data['click_set'] = 1;
		$data['site_set'] = 0;
		$data['atc_set'] = 0;
		$data['gubun'] = '';
		$data['rname'] = '';
		$data['mtype'] = 0;
		$data['u_idx'] = 1;
		$data['g_idx'] = 1;
		$data['msgflag'] = 0;
		$data['content'] = $option['content'];

		$origin_dbprefix = $this->CI->db->dbprefix;
		$this->CI->db->dbprefix = '';

		$this->CI->db->query('INSERT INTO dmail.im_seq_dmail_info_2 (idx) VALUES (\'0\')');
		$data['seqidx'] = $this->CI->db->insert_id();

		$model = array(
			'from' => 'dmail.im_dmail_info_2',
			'data' => $data,
			'include_seq' => FALSE,
			'include_time' => FALSE,
			'include_company' => FALSE
		);
		$this->CI->save_row($model);

		$this->CI->db->dbprefix = $origin_dbprefix;

		return TRUE;
	}

}
