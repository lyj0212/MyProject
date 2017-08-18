<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 페이징 Class
 *
 * @access public
 * @param integer $total_cnt 게시물 전체 갯수
 * @param integer $list_cnt 한페이지에 보여질 게시물 갯수
 * @param integer $page_nav_cnt [1][2]...[10] 갯수
 * @param integer $cur_page 현재 링크의 page segment
 * @param integer $t_obj [1][2]...[10] 갯수
 * @keyword paging
 * @return object view/paging 스킨 참고
 *
 * @todo 2008-09-19, constructed
 *
 * @author Shin dong-uk <ojm1004@nate.com>
 * @copyright Copyright (c) 2008, Plani
**/
class Pagenav {

	var $CI;
	var $segments = "";

	function __construct()
	{
		log_message('debug', "Pagenav Class Initialized");

		$this->CI = & get_instance();
	}

	function get($total_cnt, $list_cnt=20, $action='index', $page_nav_cnt='', $cur_page='')
	{
		$cur_page = $this->CI->link->get_segment('page', FALSE);
		$total_page = (int)(($total_cnt-1)/$list_cnt)+1;
		if($cur_page=='last') $cur_page = $total_page;
		if(empty($page_nav_cnt))
		{
			if(isset($this->CI->is_mobile) AND $this->CI->is_mobile == TRUE)
			{
				$page_nav_cnt = 5;
			}
			else
			{
				$page_nav_cnt = 10;
			}
		}

		if(empty($cur_page) || $cur_page<1)
		{
			$cur_page = 1;
		}
		elseif($cur_page > $total_page)
		{
			$cur_page = $total_page;
		}

		if($cur_page > $total_page - floor($page_nav_cnt/2))
		{
			$end_page = $total_page;
			$start_page = $end_page - $page_nav_cnt +1;
			if($start_page<1) $start_page = 1;
		}
		else
		{
			$start_page = $cur_page - floor($page_nav_cnt/2);
			if($start_page<1) $start_page = 1;

			$end_page = $start_page + $page_nav_cnt -1;
			if($end_page>$total_page) $end_page = $total_page;
		}

		$obj = new stdClass();

		if($cur_page>1)
		{
			$obj->prev_page = $this->CI->link->get(array('action'=>$action, 'page'=>$cur_page-1, 'id'=>NULL), FALSE);
			$obj->first_page = $this->CI->link->get(array('action'=>$action, 'page'=>NULL, 'id'=>NULL), FALSE);
		}

		if($cur_page<$total_page)
		{
			$obj->next_page = $this->CI->link->get(array('action'=>$action, 'page'=>$cur_page+1, 'id'=>NULL), FALSE);
			$obj->last_page = $this->CI->link->get(array('action'=>$action, 'page'=>$total_page, 'id'=>NULL), FALSE);
		}

		for($i=$start_page; $i<=$end_page; $i++)
		{
			$obj->page_list[$i]['num'] = $i;
			$obj->page_list[$i]['link'] = $this->CI->link->get(array('action'=>$action, 'page'=>$i, 'id'=>NULL), FALSE);
		}

		$obj->cur_page = $cur_page;

		$obj->start_cnt = ($cur_page-1)*$list_cnt;
		$obj->list_cnt  = $list_cnt;

		$obj->v_no = $total_cnt - ( ($cur_page-1)*$list_cnt);

		$obj->total_page = $total_page;
		$obj->total_cnt = $total_cnt;
		$paging['page'] = $obj;

		return $paging;
	}

}
