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
 * Main Class
 *
 * 메인페이지
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Main extends PL_Controller {

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
	public function admin()
	{
		$year = $this->link->get_segment('year', FALSE);
		$month = $this->link->get_segment('month', FALSE);
		$day = $this->link->get_segment('day', FALSE);

		if(empty($year)) $year = mdate('%Y');
		if(empty($month)) $month = mdate('%m');
		if(empty($day)) $day = mdate('%d');

		$month = sprintf('%02d', $month);
		$day = sprintf('%02d', $day);

		$date = mdate('%Y-%m-%d', mktime(0, 0, 0, $month, $day, $year));
		$today = mdate('%Y-%m-%d');
		$first_date = mdate('%Y-%m-01', mktime(0, 0, 0, $month, $day, $year));
		$last_date = mdate('%Y-%m-%t', mktime(0, 0, 0, $month, $day, $year));

        $model = array(
          'select' => 'hit, mobile_hit',
          'from' => 'log_date',
          'conditions' => array('where' => array('date' => $today))
        );
        $date_data = $this->get_row($model);

		$data['today_pc'] = ( ! empty($date_data['data']['hit'])) ? $date_data['data']['hit'] : 0;
		$data['today_mobile'] = ( ! empty($date_data['data']['mobile_hit'])) ? $date_data['data']['mobile_hit'] : 0;

        $model = array(
          'select_sum' => array('hit', 'mobile_hit'),
          'from' => 'log_date'
        );
        $total_data = $this->get_row($model);

		$data['total_pc'] = ( ! empty($total_data['data']['hit'])) ? $total_data['data']['hit'] : 0;
		$data['total_mobile'] = ( ! empty($total_data['data']['mobile_hit'])) ? $total_data['data']['mobile_hit'] : 0;

        $model = array(
          'select_sum' => array('hit', 'mobile_hit'),
          'from' => 'log_date',
          'conditions' => array('where' => array('date >=' => $first_date, 'date <=' => $last_date))
        );
        $month_data = $this->get_row($model);

		$data['month_pc'] = ( ! empty($month_data['data']['hit'])) ? $month_data['data']['hit'] : 0;
		$data['month_mobile'] = ( ! empty($month_data['data']['mobile_hit'])) ? $month_data['data']['mobile_hit'] : 0;

        $model = array(
          'select' => 'COUNT(id) as rows, agent',
          'from' => 'log_visitor',
          'conditions' => array('where' => array('date >=' => $first_date, 'date <=' => $last_date)),
          'group' => 'agent',
          'order' => array('rows' => 'DESC'),
          'limit' => 10
        );
        $browser = $this->get_entries($model);

        $model = array(
          'select' => 'COUNT(id) as rows, platform',
          'from' => 'log_visitor',
          'conditions' => array('where' => array('date >=' => $first_date, 'date <=' => $last_date)),
          'group' => 'platform',
          'order' => array('rows' => 'DESC'),
          'limit' => 10
        );
        $platform = $this->get_entries($model);

        $model = array(
          'select' => 'site',
          'select_sum' => 'hit',
          'from' => 'log_search',
          'conditions' => array('where' => array('year' => $year, 'month' => $month, 'keyword !=' => '')),
          'group' => 'site',
          'order' => array('hit' => 'DESC'),
          'limit' => 10
        );
        $search_data = $this->get_entries($model);

        $model = array(
          'select' => 'keyword',
          'select_sum' => 'hit',
          'from' => 'log_search',
          'conditions' => array('where' => array('year' => $year, 'month' => $month, 'keyword !=' => '')),
          'group' => 'keyword',
          'order' => array('hit' => 'DESC'),
          'limit' => 10
        );
        $keyword = $this->get_entries($model);

		$data['browser_json'] = array();
		foreach($browser['data'] as $item)
		{
			$data['browser_json'][] = sprintf('[\'%s\', %s]', $item['agent'], $item['rows']);
		}

		$data['platform_json'] = array();
		foreach($platform['data'] as $item)
		{
			$data['platform_json'][] = sprintf('[\'%s\', %s]', $item['platform'], $item['rows']);
		}

		$data['browser_data'] = $browser['data'];
		$data['platform_data'] = $platform['data'];
        $data['search_data'] = $search_data['data'];
		$data['keyword_data'] = $keyword['data'];

		$data['year'] = $year;
		$data['month'] = $month;

		$this->html['css'][] = '/_kor/modules/main/css/dashboard.css';
		$this->html['css'][] = '/_kor/modules/main/css/jquery.jqplot.min.css';

		$this->html['js'][] = '/_kor/modules/main/js/jquery.jqplot.min.js';
		$this->html['js'][] = '/_kor/modules/main/js/jqplot.json2.min.js';
		$this->html['js'][] = '/_kor/modules/main/js/jqplot.dateAxisRenderer.min.js';
		$this->html['js'][] = '/_kor/modules/main/js/jqplot.pieRenderer.min.js';
		$this->html['js'][] = '/_kor/modules/main/js/jqplot.highlighter.min.js';
		$this->html['js'][] = '/_kor/modules/main/js/jqplot.pointLabels.min.js';

		$view = array(
			'skin' => 'main/admin',
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
	public function json_data()
	{
		$year = $this->link->get_segment('year', FALSE);
		$month = $this->link->get_segment('month', FALSE);
		$day = $this->link->get_segment('day', FALSE);

		if(empty($year)) $year = mdate('%Y');
		if(empty($month)) $month = mdate('%m');
		if(empty($day)) $day = mdate('%d');

		$first_date = mdate('%Y-%m-01', mktime(0, 0, 0, $month, $day, $year));
		$last_date = mdate('%Y-%m-%t', mktime(0, 0, 0, $month, $day, $year));
		$last_day = mdate('%t', mktime(0, 0, 0, $month, $day, $year));

        $model = array(
          'select' => 'hit, mobile_hit, date',
          'from' => 'log_date',
          'conditions' => array('where' => array('date >=' => $first_date, 'date <=' => $last_date)),
        );
        $visitors = $this->get_entries($model);

		$data['visitors'] = array();
		foreach($visitors['data'] as $item)
		{
			$data['visitors'][$item['date']] = $item;
		}

		$json_data = array();
		foreach(range(1, $last_day) as $sKey)
		{
			$date = date('Y-m-d', mktime(0, 0, 0, $month, $sKey, $year));
			$theday = date('d-M-Y', mktime(0, 0, 0, $month, $sKey, $year));
			$json_data[] = array($theday, (isset($data['visitors'][$date])) ? $data['visitors'][$date]['hit']+$data['visitors'][$date]['mobile_hit'] : 0);
		}

		echo sprintf('[%s]', json_encode($json_data));
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the Controller Preferences
	 *
	 * @access	private
	 * @params array
	 * @return	void
	 */
	public function korean()
	{
		
		//공지사항
		$model = array(
			'from' => 'notice A',
			'limit' => 6,
			'order' => array('A.created'=>'DESC'),
		);
		$data['board'] = $this->get_entries($model);


        $this->html['js'][] = '/_res/js/script_main.js';
        $this->html['js'][] = '/_res/js/jquery.fullPage.min.js';
        $this->html['css'][] = '/_res/js/jquery.fullPage.css';

		$view = array(
			'skin' => 'main/korean',
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
  public function construction()
  {
    $this->html['css'][] = './modules/main/css/construction.css';

    $view = array(
      'skin' => 'main/construction',
      'layout' => FALSE
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
	protected function _process_entries(&$item, $data)
	{
		$width = 198;
		$height = 93;

		$thumb = $this->image_lib->noimg($width, $height);

		if( ! empty($data['files'][$item['id']]))
		{
			foreach($data['files'][$item['id']] as $file)
			{
				if(in_array($file['file_ext'], array('.jpg', '.gif', '.png')) == TRUE)
				{
					$config = array(
						'source_image' => $file['upload_path'].$file['raw_name'].$file['file_ext'],
						'new_image' => sprintf('%sthumbs/%s_%s_%s%s', $file['upload_path'], $file['raw_name'], $width, $height, $file['file_ext']),
						'width' => $width,
						'height' => $height,
						'maintain_ratio' => FALSE
					);
					$thumb = $this->image_lib->thumb($config);
				}
			}
		}

		$item['thumb'] = site_url($thumb);
	}

}
