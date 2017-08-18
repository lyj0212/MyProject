<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['list_section'] = array(
	'no' => array('name'=>'번호', 'size'=>'70', 'class'=>'hidden-xs'),
	'category' => array('name'=>'분류', 'size'=>'100', 'class'=>''),
	'subject' => array('name'=>'제목', 'size'=>'*', 'class'=>'subject'),
	'name' => array('name'=>'이름', 'size'=>'120', 'class'=>'name'),
	'ext' => array('name'=>'파일', 'size'=>'100', 'class'=>'hidden-xs'),
	'thumb' => array('name'=>'섬네일', 'size'=>'100', 'class'=>''),
	'hit' => array('name'=>'조회수', 'size'=>'80', 'class'=>'hidden-xs hit'),
	'status' => array('name'=>'상태', 'size'=>'70', 'class'=>''),
	'created' => array('name'=>'등록일', 'size'=>'120', 'class'=>'hidden-xs created'),
	'modified' => array('name'=>'수정일', 'size'=>'120', 'class'=>'hidden-xs modified'),
);
