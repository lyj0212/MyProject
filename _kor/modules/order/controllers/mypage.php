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
        $this->cache->delete('_module_config_order');
        $this->title = '발주현황 한눈에 보기';

        $model = array(
            'from' => 'order',
            'order' => array('id' => 'ASC'),
        );

        $data = $this->get_entries($model);

        $view = array(
            'skin' => '/mypage/index',
            'data' => $data
        );
        $this->show($view);
    }

}