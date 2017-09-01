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
class Order extends PL_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_initialize();
    }

    private function _initialize()
    {
        $this->load->library('image_lib');
    }

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
        $this->title = '발주관리시스템';

        $model = array(
            'from' => 'order A',
            'limit' => 20,
            'paging' => TRUE,
            'paging_action' => 'index',
            'order' => array('A.id' => 'ASC'),
            'include_file' => TRUE,
            'include_readed' => TRUE,
            'include_search' => TRUE,
            'callback' => '_process_entries'
        );
        if(get_field('search_type'))
        {
            $model['conditions']['where']['A.type'] = get_field('search_type');
        }

        $data = $this->get_entries($model);

        $model = array(
            'from' => 'order A',
        );
        $row = $this->get_entries($model);
        $data['total'] = count($row['data']);

        $view = array(
            'skin' => '/order/index',
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
            'from' => 'order',
            'conditions' => array('where' => array('id' => $id)),
            'include_file' => TRUE,
            //'update_readed'  => TRUE,
            'callback' => '_process_entries',
            'not_exists' => '_error_exists'
        );
        $data = $this->get_row($model);

        $model = array(
            'from' => 'global_category',
            'conditions' => array('where' => array('type' => 'light')),
            'hasmany' => array(
                array(
                    'match' => 'category',
                    'from' => 'order_category A',
                    'conditions' => array('where' => array('A.order_id' => $id)),
                    'return' => 'light_data'
                )
            ),
            'order' => array('sort' => 'ASC'),
            'callback' => '_process_category'
        );
        $data['light_list'] = $this->get_entries($model);

        $hit = $this->session->userdata('hit');
        if(empty($hit))
        {
            $hit = array();
        }
        if( ! in_array($id, $hit))
        {
            $model = array(
                'from' => 'order',
                'conditions' => array('where' => array('id' => $id)),
                'data' => array('hit' => $data['data']['hit']+1),
                'include_time' => FALSE
            );
            $this->save_row($model);

            $hit[] = $id;
            $this->session->set_userdata('hit', $hit);
        }

        $view = array(
            'skin' => '/order/view',
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
            $model = array(
                'from' => 'order',
                'conditions' => array('where' => array('id' => $id)),
                'include_file' => 'ALL',
                'not_exists' => '_error_exists'
            );
            $data = $this->get_row($model);


            $data['light_list'] =$this->_itemlist('light', $id);
            $data['switch_list'] =$this->_itemlist('switch', $id);
            $data['switchetc_list'] =$this->_itemlist('switchetc', $id);
            $data['socket_list'] =$this->_itemlist('socket', $id);
            $data['sensor_list'] =$this->_itemlist('sensor', $id);
            $data['videophone_list'] =$this->_itemlist('videophone', $id);
            $data['doorlock_list'] =$this->_itemlist('doorlock', $id);

        }
        else
        {
            $data = $this->set_default('order');

            $data['light_list'] = $this->_category('light');
            $data['light_list']['order_data'] = $this->set_default('order_category');
            $data['switch_list'] = $this->_category('switch');
            $data['switch_list']['order_data'] = $this->set_default('order_category');
            $data['switchetc_list'] = $this->_category('switchetc');
            $data['switchetc_list']['order_data'] = $this->set_default('order_category');
            $data['socket_list'] = $this->_category('socket');
            $data['socket_list']['order_data'] = $this->set_default('order_category');
            $data['sensor_list'] = $this->_category('sensor');
            $data['sensor_list']['order_data'] = $this->set_default('order_category');
            $data['videophone_list'] = $this->_category('videophone');
            $data['videophone_list']['order_data'] = $this->set_default('order_category');
            $data['doorlock_list'] = $this->_category('doorlock');
            $data['doorlock_list']['order_data'] = $this->set_default('order_category');

        }
        $view = array(
            'skin' => '/order/write',
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
        $this->form_validation->set_rules('title', '제목', 'trim|required');
        $this->form_validation->set_rules('company', '고객사명', 'trim|required');
        $this->form_validation->set_rules('phone2', '두번째 연락처', 'trim|required');
        $this->form_validation->set_rules('phone3', '세번째 연락처', 'trim|required');

        if($this->form_validation->run() === FALSE)
        {
            $this->write();
            return FALSE;
        }

        $id = $this->input->post('id');
        $data['title'] = $this->input->post('title');
        $data['company'] = $this->input->post('company');
        $data['name'] = $this->input->post('name');
        $data['phone1'] = $this->input->post('phone1');
        $data['phone2'] = $this->input->post('phone2');
        $data['phone3'] = $this->input->post('phone3');
        $data['contents'] = $this->input->post('contents');
        $data['contents'] = preg_replace(sprintf("|src=[\"']?%s(.*?)[\"']|i", rtrim(site_url(), '/')), "src=\"$1\"", $data['contents']);
        $data['contents'] = preg_replace(sprintf("|href=[\"']?%s(.*?)[\"']|i", rtrim(site_url(), '/')), "href=\"$1\"", $data['contents']);
        $data['delivery_area'] = $this->input->post('delivery_area');
        $data['delivery_date'] = $this->input->post('delivery_date');
        $data['build_date'] = $this->input->post('build_date');
        $data['message'] = $this->input->post('message');

        if(empty($id))
        {
            $data['ismember'] = $this->account->get('id');
            $data['ip'] = $this->input->ip_address();
        }

        $model = array(
            'from' => 'order',
            'conditions' => ( ! empty($id)) ? array('where'=>array('id'=>$id)) : NULL,
            'data' => $data,
            'include_file' => TRUE,
            'update_readed' => TRUE
        );
        $affect_id = $this->save_row($model);


        //조명 입력
        $model = array(
            'from' => 'global_category',
            'conditions' => array('where' => array('type' => 'light')),
            'order' => array('sort' => 'ASC'),
            'callback' => '_process_category'
        );
        $light_list = $this->get_entries($model);

        foreach ($light_list['data'] as $item){
            if($this->input->post('title_'.$item['id']) != ''){
                $coid = $this->input->post('coid_'.$item['id']);
                $datas['title'] = $this->input->post('title_'.$item['id']);
                $datas['cnt'] = $this->input->post('cnt_'.$item['id']);
                $datas['color'] = $this->input->post('color_'.$item['id']);
                $datas['lamp'] = $this->input->post('lamp_'.$item['id']);

                $model = array(
                    'from' => 'order_category',
                    'conditions' => array('where' => array('id' => $coid)),
                    'data' => $datas
                );
                if(empty($coid)){
                    $datas['order_id'] = $affect_id;
                    $datas['category'] = $item['id'];
                    $model = array(
                        'from' => 'order_category',
                        'data' => $datas
                    );
                }else{
                    $model = array(
                        'from' => 'order_category',
                        'conditions' => array('where' => array('id' => $coid)),
                        'data' => $datas
                    );
                }
                $this->save_row($model);
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
    public function order_save()
    {
        $id = $this->input->post('id');
        $data['etc'] = $this->input->post('etc');
        $data['type'] = $this->input->post('type');

        $model = array(
            'from' => 'order',
            'conditions' => array('where'=>array('id'=>$id)),
            'data' => $data,
        );
        $affect_id = $this->save_row($model);

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
            'from' => 'order',
            'conditions' => array('where' => array('id' => $id)),
            'not_exists' => '_error_exists'
        );
        $data = $this->get_row($model);

        $model = array(
            'from' => 'order',
            'conditions' => array('where' => array('id' => $id))
        );
        $this->delete_row($model);

        $model = array(
            'from' => 'comment',
            'conditions' => array('where' => array('pid' => $id))
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
    public function change_status()
    {
        $id = $this->link->get_segment('id');
        $type = $this->input->post('type');

        $model = array(
            'from' => 'order',
            'conditions' => array('where' => array('id' => $id)),
            'data' => array('type' => $type)
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

    protected function _itemlist($type, $id)
    {
        $model = array(
            'from' => 'global_category',
            'conditions' => array('where' => array('type' => $type)),
            'hasmany' => array(
                array(
                    'match' => 'category',
                    'from' => 'order_category A',
                    'conditions' => array('where' => array('A.order_id' => $id)),
                    'return' => 'order_data'
                )
            ),
            'order' => array('sort' => 'ASC'),
            'callback' => '_process_category'
        );
        return $this->get_entries($model);
    }

    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access	private
     * @params array
     * @return	void
     */

    protected function _category($type)
    {
        $model = array(
            'from' => 'global_category',
            'conditions' => array('where' => array('type' => $type)),
            'order' => array('sort' => 'ASC'),
            'callback' => '_process_category'
        );
        return $this->get_entries($model);
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
        $width = 300;
        $height = 200;
        if(isset($data['files'][$item['id']][0]))
        {
            $file = $data['files'][$item['id']][0];
            $config = array(
                'source_image' => $file['upload_path'].$file['raw_name'].$file['file_ext'],
                'new_image' => sprintf('%sthumbs/%s_%s_%s%s', $file['upload_path'], $file['raw_name'], $width, $height, $file['file_ext']),
                'width' => $width,
                'height' => $height,
                'maintain_ratio' => FALSE
            );
            $thumb = $this->image_lib->thumb($config);
        }
        else
        {
            $thumb = $this->image_lib->noimg($width, $height);
        }

        $item['thumb'] = site_url($thumb);
    }

    // --------------------------------------------------------------------

    /**
     * Initialize the Controller Preferences
     *
     * @access	private
     * @params array
     * @return	void
     */
    protected function _error_exists_setup()
    {
        script('back', '게시판이 존재하지 않습니다.');
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

}