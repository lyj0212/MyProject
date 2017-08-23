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
 * Menu Class
 *
 * @package	Plani Module
 * @subpackage	 Controller
 * @category	Controller
 * @author	Shin Donguk
 */
class Mypage extends PL_Controller
{

    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access    private
     * @params array
     * @return    void
     */
    public function index()
    {
        $this->title = '발주현황 한눈에 보기';

        $model = array(
            'from' => 'bbs',
            'conditions' => array('where' => array('tableid' => 'notice')),
            'order' => array('id' => 'ASC'),
        );
        $data['notice'] = $this->get_entries($model);

        $model = array(
            'select' => 'count(id) as cnt, type',
            'from' => 'order',
            'order' => array('id' => 'ASC'),
            'group' => 'type'
        );
        $row = $this->get_entries($model);

        $data['order1'] = 0;
        $data['order2'] = 0;
        $data['order3'] = 0;

        foreach ($row['data'] as $item){
            if($item['type'] == '대기')
                $data['order1'] = $item['cnt'];
            else if($item['type'] == '완료')
                $data['order2'] = $item['cnt'];
            else if($item['type'] == '취소')
                $data['order3'] = $item['cnt'];
        }

        $view = array(
            'skin' => '/mypage/index',
            'data' => $data
        );
        $this->show($view);
    }

}