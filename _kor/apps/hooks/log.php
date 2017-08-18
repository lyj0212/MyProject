<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 로그(접속 통계)
 *
 * @todo 2013-07-17, constructed
 * @keyword log
 *
 * @author Shin dong-uk <uks@plani.co.kr>
 * @copyright Copyright (c) 2010, Plani
 **/
class Log {

	private $CI;
	private $ip;
	private $today;

	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->helper('date');
		$this->CI->load->library('user_agent');
	}

	public function setup()
	{
		/**
		 * 검색엔진 로봇일 경우 기록하지 않는다.
		 **/
		if($this->CI->agent->is_robot() == TRUE)
		{
			return FALSE;
		}

		/**
		 * 방문자 기록
		 **/
		if(get_cookie('visitor_checked') == FALSE)
		{
			$this->ip = $this->CI->input->ip_address();
			$this->today = mdate('%Y-%m-%d');
			// 기록 시작
			$this->__register_log();
		}

		/**
		 * 검색 레퍼러 기록
		 **/
		if($this->CI->agent->is_referral() == TRUE)
		{
			$this->__register_search();
		}
	}

	/**
	 * 접속자 정보 저장
	 *
	 * @access private
	 */
	private function __register_visitor()
	{
		$model = array(
			'select' => 'id',
			'from' => 'log_visitor',
			'conditions' => array(
				'where' => array(
					'ip' => $this->ip,
					sprintf('date BETWEEN \'%s\' AND \'%s\'', $this->today, mdate('%Y-%m-%d', strtotime('+1 days')))
				)
			)
		);
		if($this->CI->check_exists($model) == FALSE)
		{
			$insert_visitor['ip'] = $this->ip;
			$insert_visitor['agent'] = $this->__check_agent();
			$platform = $this->CI->agent->platform();
			$insert_visitor['platform'] = ( ! empty($platform)) ? $platform : 'Unidentified';
			$insert_visitor['date'] = mdate('%Y-%m-%d %H:%i:%s');

			$model = array(
				'from' => 'log_visitor',
				'data' => $insert_visitor,
				'include_time' => FALSE
			);
			$this->CI->save_row($model);

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	private function __check_agent()
	{
		if($this->CI->agent->is_mobile())
		{
			$agent = $this->CI->agent->mobile();
		}
		elseif($this->CI->agent->is_robot())
		{
			$agent = $this->CI->agent->robot();
		}
		elseif($this->CI->agent->is_browser())
		{
			$agent = $this->CI->agent->browser().' '.$this->CI->agent->version();
		}
		else
		{
			$agent = 'Unidentified';
		}

		return $agent;
	}

	/**
	 * 날짜별 카운터
	 *
	 * @access private
	 */
	private function __register_date()
	{
		$model = array(
			'select' => 'date',
			'from' => 'log_date',
			'conditions' => array(
				'where' => array(
					'date' => $this->today
				)
			)
		);
		if($this->CI->check_exists($model) == FALSE)
		{
			$model = array(
				'select_sum' => array('hit', 'mobile_hit'),
				'from' => 'log_date'
			);
			$row = $this->CI->get_row($model);

			$total_hit = ($row['data']['hit'] > 0) ? $row['data']['hit'] : 0;
			$total_mobile_hit = ($row['data']['mobile_hit'] > 0) ? $row['data']['mobile_hit'] : 0;

			if($this->CI->agent->is_mobile())
			{
				$insert_date['mobile_hit'] = 1;
			}
			else
			{
				$insert_date['hit'] = 1;
			}
			$insert_date['ac_hit'] = $total_mobile_hit + $total_hit + 1;
			$insert_date['date'] = $this->today;

			$model = array(
				'from' => 'log_date',
				'data' => $insert_date,
				'include_time' => FALSE
			);
			$this->CI->save_row($model);
		}
		else
		{
			$model = array(
				'from' => 'log_date',
				'conditions' => array('where' => array('date' => $this->today)),
				'set' => array('ac_hit' => 'ac_hit+1'),
				'include_time' => FALSE
			);

			if($this->CI->agent->is_mobile() == TRUE)
			{
				$model['set'] = array_merge(array('mobile_hit' => 'mobile_hit+1'), $model['set']);
			}
			else
			{
				$model['set'] = array_merge(array('hit' => 'hit+1'), $model['set']);
			}

			$this->CI->save_row($model);
		}

		return TRUE;
	}

	/**
	 * 로그 기록
	 *
	 * @access private
	 */
	private function __register_log()
	{
		if($this->__register_visitor() == TRUE)
		{
			$this->__register_date();
		}

		$cookie = array(
			'name' => 'visitor_checked',
			'value' => '1',
			'expire' => mktime(0, 0, 0, mdate('%m'), mdate('%d'), mdate('%Y')) + 86400 - now()
		);
		set_cookie($cookie);
	}

	/**
	 * 검색엔진 referer 기록
	 *
	 * @access private
	 **/
	private function __register_search()
	{
		$this->CI->load->config('search_engine');
		$engine = $this->CI->config->item('search_engine');
		$url_info = parse_url($this->CI->agent->referrer());

		if( ! empty($url_info['host']))
		{
			$url_host = explode('.', $url_info['host']);
		}

		if( ! empty($url_info['query']))
		{
			$url_query = explode('&', $url_info['query']);
		}

		foreach($url_host as $h)
		{
			if(isset($engine[$h]) && is_array($engine[$h]))
			{
				if( ! empty($url_query))
				{
					foreach($url_query as $q)
					{
						$d_q = explode('=', $q);

						if($engine[$h][1] == $d_q[0])
						{
							$search['site'] = $engine[$h][0];

							$keyword = urldecode($d_q[1]);
							if(isset($engine[$h][2]))
							{
								$keyword = iconv($engine[$h][2], 'utf-8', $keyword);
							}
							$search['keyword'] = trim($keyword);
							$search['year'] = mdate('%Y');
							$search['month'] = mdate('%m');
							$search['day'] = mdate('%d');

							$model = array(
								'select' => 'id',
								'from' => 'log_search',
								'conditions' => array('where' => $search)
							);
							$row = $this->CI->get_row($model);

							if( ! empty($row['data']['id']))
							{
								$model = array(
									'from' => 'log_search',
									'conditions' => array('where' => array('id' => $row['data']['id'])),
									'set' => array('hit' => 'hit+1'),
									'include_time' => FALSE
								);
								$this->CI->save_row($model);
							}
							else
							{
								$search['from'] = 'log_search';
								$search['include_time'] = FALSE;
								$this->CI->save_row($search);
							}
						}
					}
				}
			}
		}
	}

}
