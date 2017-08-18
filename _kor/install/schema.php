<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * i-Smart Code Schema Info
 *
 * @todo 2013-02-20, constructed
 * @keyword description
 *
 * @author Shin dong-uk <uks@plani.co.kr>
 * @copyright Copyright (c) 2009, Plani
**/

$schema['sequence'] = "CREATE TABLE `{$dbprefix}sequence` (
  `seq` bigint(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '자동증가값',
  `nonce` char(1) DEFAULT NULL COMMENT '임시값',
  PRIMARY KEY (`seq`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['sessions'] = "CREATE TABLE `{$dbprefix}sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0' COMMENT '세션 아이디',
  `ip_address` varchar(45) NOT NULL DEFAULT '0' COMMENT '아이피',
  `user_agent` varchar(150) NOT NULL COMMENT '사용자 에이전트',
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '세션 갱신 시간',
  `user_data` mediumtext COMMENT '세션 데이터',
  `scriptdata` mediumtext COMMENT 'module javascript 변수',
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['log_visitor'] = "CREATE TABLE `{$dbprefix}log_visitor` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `ip` char(15) NOT NULL COMMENT '아이피',
  `agent` varchar(150) NOT NULL COMMENT '사용자 에이전트',
  `platform` varchar(100) NOT NULL COMMENT '사용자 플랫폼',
  `date` datetime NOT NULL COMMENT '날짜',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['log_search'] = "CREATE TABLE `{$dbprefix}log_search` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `site` varchar(100) NOT NULL COMMENT '검색 사이트',
  `keyword` varchar(250) NOT NULL COMMENT '검색 키워드',
  `hit` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '검색 횟수',
  `year` char(4) NOT NULL COMMENT '년',
  `month` char(2) NOT NULL COMMENT '월',
  `day` char(2) NOT NULL COMMENT '일',
  PRIMARY KEY (`id`),
  KEY `year` (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['log_date'] = "CREATE TABLE `{$dbprefix}log_date` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `hit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'PC 접속 횟수',
  `mobile_hit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '모바일 접속 횟수',
  `ac_hit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '누적 접속 횟수',
  `date` varchar(10) NOT NULL COMMENT '날짜',
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['menu'] = "CREATE TABLE `{$dbprefix}menu` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `title` varchar(100) NOT NULL COMMENT '맵 이름',
  `map` varchar(20) DEFAULT NULL COMMENT '맵 prefix',
  `isadmin` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '관리자 사이트 맵인지 여부',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['menu_item'] = "CREATE TABLE `{$dbprefix}menu_item` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `menu_id` bigint(11) unsigned NOT NULL COMMENT '맵이름',
  `parent_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '부모번호',
  `title` varchar(150) NOT NULL COMMENT '메뉴명',
  `url` varchar(50) DEFAULT NULL COMMENT '메뉴URL',
  `link` varchar(150) DEFAULT NULL COMMENT '사용자링크',
  `win_target` varchar(30) DEFAULT NULL COMMENT '사용자링크타겟',
  `layout` int(10) unsigned DEFAULT NULL COMMENT '레이아웃번호',
  `target` varchar(100) DEFAULT NULL COMMENT '사용모듈',
  `param` varchar(50) DEFAULT NULL COMMENT '기본파라미터',
  `sort` smallint(3) unsigned NOT NULL COMMENT '정렬번호',
  `hidden` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '메뉴 감춤 여부',
  `index` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '해당맵의 첫페이지로 설정 여부',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['files'] = "CREATE TABLE `{$dbprefix}files` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `pid` bigint(11) unsigned NOT NULL COMMENT '문서번호',
  `target` varchar(50) DEFAULT NULL COMMENT '상세구분',
  `orig_name` varchar(250) NOT NULL COMMENT '파일실제이름',
  `raw_name` varchar(250) NOT NULL COMMENT '파일이름',
  `file_ext` char(10) DEFAULT NULL COMMENT '확장자',
  `file_size` varchar(20) NOT NULL DEFAULT '0' COMMENT '파일크기',
  `upload_path` varchar(250) NOT NULL COMMENT '파일경로',
  `download_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '다운로드 횟수',
  `use` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '실제문서에서의 사용여부',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`,`use`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['global_category'] = "CREATE TABLE `{$dbprefix}global_category` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `parent_id` bigint(11) unsigned NOT NULL COMMENT '부모번호',
  `type` varchar(50) NOT NULL COMMENT '카테고리타입',
  `title` varchar(150) DEFAULT NULL COMMENT '타이틀',
  `sort` smallint(3) unsigned NOT NULL COMMENT '정렬순서',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['layouts'] = "CREATE TABLE `{$dbprefix}layouts` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `title` varchar(100) NOT NULL COMMENT '타이틀',
  `source` varchar(150) NOT NULL COMMENT '소스파일',
  `top_menu` varchar(150) DEFAULT NULL COMMENT '상단메뉴파일',
  `top_menu_depth` varchar(150) DEFAULT NULL COMMENT '상단메뉴재귀파일',
  `sub_menu` varchar(150) DEFAULT NULL COMMENT '서브메뉴파일',
  `sub_menu_depth` varchar(150) DEFAULT NULL COMMENT '서브메뉴재귀파일',
  `popup` varchar(150) DEFAULT NULL COMMENT '팝업레이아웃파일',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['member'] = "CREATE TABLE `{$dbprefix}member` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `group` bigint(11) unsigned NOT NULL COMMENT '회원그룹',
  `department` bigint(11) unsigned DEFAULT NULL COMMENT '부서번호',
  `position` bigint(11) unsigned DEFAULT NULL COMMENT '직위',
  `name` varchar(50) NOT NULL COMMENT '이름',
  `jumin1` char(6) DEFAULT NULL COMMENT '주민번호1',
  `jumin2` varchar(100) DEFAULT NULL COMMENT '주민번호2',
  `userid` varchar(50) NOT NULL COMMENT '사용자아이디',
  `passwd` varchar(100) NOT NULL COMMENT '비밀번호',
  `phone1` char(4) DEFAULT NULL COMMENT '전화번호1',
  `phone2` char(4) DEFAULT NULL COMMENT '전화번호2',
  `phone3` char(4) DEFAULT NULL COMMENT '전화번호3',
  `handphone1` char(3) DEFAULT NULL COMMENT '핸드폰1',
  `handphone2` char(4) DEFAULT NULL COMMENT '핸드폰2',
  `handphone3` char(4) DEFAULT NULL COMMENT '핸드폰3',
  `email` varchar(150) DEFAULT NULL COMMENT '이메일',
  `zipcode1` char(3) DEFAULT NULL COMMENT '우편번호1',
  `zipcode2` char(3) DEFAULT NULL COMMENT '우편번호2',
  `address1` varchar(150) DEFAULT NULL COMMENT '주소1',
  `address2` varchar(150) DEFAULT NULL COMMENT '주소2',
  `memo` text COMMENT '자기소개',
  `last_login` datetime DEFAULT NULL COMMENT '마지막로그인',
  `deleted` datetime DEFAULT NULL COMMENT '탈퇴일',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['member_group'] = "CREATE TABLE `{$dbprefix}member_group` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `title` varchar(50) NOT NULL COMMENT '그룹명',
  `sort` smallint(3) unsigned NOT NULL COMMENT '정렬순서',
  `admin` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '관리자그룹여부',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['message'] = "CREATE TABLE `{$dbprefix}message` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `related_id` bigint(11) unsigned NOT NULL COMMENT '연관번호',
  `type` enum('R','S') NOT NULL COMMENT '수신/발신여부',
  `member_id` bigint(11) unsigned NOT NULL COMMENT '회원번호',
  `sender_id` bigint(11) unsigned NOT NULL COMMENT '발신인번호',
  `message` text NOT NULL COMMENT '메세지',
  `delete_prepare` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '삭제대상여부',
  `readed` datetime DEFAULT NULL COMMENT '수신일',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `related_id` (`related_id`),
  KEY `owner` (`type`,`member_id`),
  KEY `sender` (`type`,`sender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['pages'] = "CREATE TABLE `{$dbprefix}pages` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `title` varchar(100) NOT NULL COMMENT '타이틀',
  `source` varchar(150) NOT NULL COMMENT '소스파일',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['permission'] = "CREATE TABLE `{$dbprefix}permission` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `menu_id` bigint(11) unsigned NOT NULL COMMENT '메뉴번호',
  `type` varchar(50) NOT NULL DEFAULT 'group' COMMENT '그룹별, 개인별 권한여부',
  `action` text NOT NULL COMMENT '모듈액션',
  `group_id` varchar(50) NOT NULL COMMENT '그룹번호',
  `delete_prepare` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '삭제대상여부',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['variable'] = "CREATE TABLE `{$dbprefix}variable` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `pid` bigint(11) unsigned NOT NULL COMMENT '부모번호',
  `variable` varchar(50) NOT NULL COMMENT '변수명',
  `value` mediumtext COMMENT '변수값',
  `sort` smallint(3) unsigned NOT NULL COMMENT '정렬순서',
  `delete_prepare` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '삭제대상여부',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['bbs'] = "CREATE TABLE `{$dbprefix}bbs` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `tableid` varchar(100) NOT NULL COMMENT '게시판 아이디',
  `ismember` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '회원여부 및 회원번호',
  `category` bigint(11) unsigned DEFAULT NULL COMMENT '카테고리',
  `fid` int(10) NOT NULL COMMENT '정렬값',
  `thread` varchar(250) NOT NULL DEFAULT 'AA' COMMENT '정렬 및 깊이',
  `subject` varchar(200) NOT NULL COMMENT '제목',
  `contents` longtext COMMENT '내용',
  `name` varchar(20) NOT NULL COMMENT '작성자',
  `passwd` varchar(50) DEFAULT NULL COMMENT '비밀번호',
  `isnotice` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '공지사항 여부',
  `issecret` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '비밀글 여부',
  `ismobile` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '모바일에서 작성 여부',
  `hit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '조회수',
  `total_comment` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '댓글갯수',
  `ip` char(15) NOT NULL COMMENT '아이피',
  `temporary` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '임시저장 여부',
  `created` datetime NOT NULL COMMENT '작성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`,`thread`),
  KEY `tableid` (`tableid`,`isnotice`),
  KEY `category` (`category`),
  KEY `ismember` (`ismember`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['bbs_category'] = "CREATE TABLE `{$dbprefix}bbs_category` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `tableid` varchar(100) NOT NULL COMMENT '게시판 아이디',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '상위 카테고리 번호',
  `title` varchar(150) NOT NULL COMMENT '제목',
  `sort` smallint(3) unsigned NOT NULL COMMENT '정렬값',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `tableid` (`tableid`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['bbs_setup'] = "CREATE TABLE `{$dbprefix}bbs_setup` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `tableid` varchar(100) NOT NULL COMMENT '게시판 아이디',
  `title` varchar(100) NOT NULL COMMENT '게시판 이름',
  `type` varchar(50) NOT NULL DEFAULT 'list' COMMENT '게시판 타입',
  `limit` smallint(3) unsigned NOT NULL DEFAULT '20' COMMENT '한페이지 출력 갯수',
  `list` text COMMENT '게시판 목록 항목',
  `use_category` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '카테고리 사용여부',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tableid` (`tableid`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['comment'] = "CREATE TABLE `{$dbprefix}comment` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `pid` bigint(11) unsigned NOT NULL COMMENT '부모글',
  `fid` int(10) NOT NULL COMMENT '답글의 부모값',
  `thread` varchar(250) NOT NULL DEFAULT 'AA' COMMENT '정렬 및 깊이',
  `ismember` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '회원번호',
  `contents` text COMMENT '글내용',
  `name` varchar(20) NOT NULL COMMENT '작성자',
  `passwd` varchar(50) DEFAULT NULL COMMENT '비밀번호',
  `mobile` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '모바일에서 작성 여부',
  `ip` varchar(15) NOT NULL COMMENT '아이피',
  `created` datetime NOT NULL COMMENT '작성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `ismember` (`ismember`),
  KEY `order` (`fid`,`thread`,`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['ftp'] = "CREATE TABLE `{$dbprefix}ftp` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `username` varchar(50) DEFAULT NULL COMMENT 'username',
  `home` varchar(150) DEFAULT NULL COMMENT 'home path',
  `port` varchar(4) DEFAULT NULL COMMENT 'port',
  `passive` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'passive',
  `created` datetime NOT NULL COMMENT '작성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['popup'] = "CREATE TABLE `{$dbprefix}popup` (
  `id` bigint(11) unsigned NOT NULL,
  `subject` varchar(150) NOT NULL,
  `contents` longtext,
  `width` varchar(6) NOT NULL,
  `height` varchar(6) NOT NULL,
  `left` varchar(6) NOT NULL,
  `top` varchar(6) NOT NULL,
  `start_date` char(10) NOT NULL,
  `end_date` char(10) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$schema['popupzone'] = "CREATE TABLE `{$dbprefix}popupzone` (
  `id` bigint(11) unsigned NOT NULL,
  `subject` varchar(150) NOT NULL,
  `sort` smallint(3) unsigned default NULL,
  `link` varchar(200) default NULL,
  `target` enum('_self','_blank') NOT NULL default '_blank',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=$dbcharset;
";

$query['layout'][] = "INSERT INTO `{$dbprefix}layouts` (`id`, `title`, `source`, `top_menu`, `top_menu_depth`, `sub_menu`, `sub_menu_depth`, `popup`, `created`, `modified`) VALUES ('{SEQ}', '관리자 레이아웃', 'attach/layout/AVKHItRblxUButG.source', 'attach/layout/menu/ufcVQiFLETbNXME.source', 'attach/layout/menu/AJmaMHBkIJwNVrk.source', 'attach/layout/menu/EPGyBHezCGOmGOb.source', 'attach/layout/menu/OrrlFadpnPiHykw.source', 'attach/layout/popup/cJrEYpkJjUIYBgW.source', '{NOW}', '{NOW}');";
$query['menu'][] = "INSERT INTO `{$dbprefix}menu` (`id`, `title`, `map`, `isadmin`, `created`, `modified`) VALUES ('{SEQ}', '관리자', 'webAdmin', 'Y', '{NOW}', '{NOW}');";
$query['menu_item_member'][] = "INSERT INTO `{$dbprefix}menu_item` (`id`, `menu_id`, `parent_id`, `title`, `url`, `layout`, `target`, `sort`, `hidden`, `index`, `created`, `modified`) VALUES ('{SEQ}', '{menu-SEQ}', '0', '회원관리', '', '{layout-SEQ}', '', '2', 'N', 'N', '{NOW}', '{NOW}');";
$query['menu_item'][] = "INSERT INTO `{$dbprefix}menu_item` (`id`, `menu_id`, `parent_id`, `title`, `url`, `layout`, `target`, `sort`, `hidden`, `index`, `created`, `modified`) VALUES ('{SEQ}', '{menu-SEQ}', '0', '대시보드', 'dashboard', '{layout-SEQ}', 'main/main/admin', '1', 'N', 'Y', '{NOW}', '{NOW}');";
$query['menu_item'][] = "INSERT INTO `{$dbprefix}menu_item` (`id`, `menu_id`, `parent_id`, `title`, `url`, `layout`, `target`, `sort`, `hidden`, `index`, `created`, `modified`) VALUES ('{SEQ}', '{menu-SEQ}', '{menu_item_member-SEQ}', '회원관리', 'member', '{layout-SEQ}', 'member/manager/index', '3', 'N', 'N', '{NOW}', '{NOW}');";
$query['menu_item'][] = "INSERT INTO `{$dbprefix}menu_item` (`id`, `menu_id`, `parent_id`, `title`, `url`, `layout`, `target`, `sort`, `hidden`, `index`, `created`, `modified`) VALUES ('{SEQ}', '{menu-SEQ}', '{menu_item_member-SEQ}', '그룹관리', 'member_group', '{layout-SEQ}', 'member/group/index', '4', 'N', 'N', '{NOW}', '{NOW}');";
$query['menu_item'][] = "INSERT INTO `{$dbprefix}menu_item` (`id`, `menu_id`, `parent_id`, `title`, `url`, `layout`, `target`, `sort`, `hidden`, `index`, `created`, `modified`) VALUES ('{SEQ}', '{menu-SEQ}', '0', '메뉴관리', 'menu', '{layout-SEQ}', 'menu/manager/index', '5', 'N', 'N', '{NOW}', '{NOW}');";
$query['menu_item'][] = "INSERT INTO `{$dbprefix}menu_item` (`id`, `menu_id`, `parent_id`, `title`, `url`, `layout`, `target`, `sort`, `hidden`, `index`, `created`, `modified`) VALUES ('{SEQ}', '{menu-SEQ}', '0', '레이아웃관리', 'layout', '{layout-SEQ}', 'layout/manager/index', '6', 'N', 'N', '{NOW}', '{NOW}');";
$query['menu_item'][] = "INSERT INTO `{$dbprefix}menu_item` (`id`, `menu_id`, `parent_id`, `title`, `url`, `layout`, `target`, `sort`, `hidden`, `index`, `created`, `modified`) VALUES ('{SEQ}', '{menu-SEQ}', '0', '게시판관리', 'board', '{layout-SEQ}', 'board/manager/index', '7', 'N', 'N', '{NOW}', '{NOW}');";
$query['menu_item'][] = "INSERT INTO `{$dbprefix}menu_item` (`id`, `menu_id`, `parent_id`, `title`, `url`, `layout`, `target`, `sort`, `hidden`, `index`, `created`, `modified`) VALUES ('{SEQ}', '{menu-SEQ}', '0', '페이지관리', 'page', '{layout-SEQ}', 'page/manager/index', '8', 'N', 'N', '{NOW}', '{NOW}');";
$query['menu_item'][] = "INSERT INTO `{$dbprefix}menu_item` (`id`, `menu_id`, `parent_id`, `title`, `url`, `layout`, `target`, `sort`, `hidden`, `index`, `created`, `modified`) VALUES ('{SEQ}', '{menu-SEQ}', '0', '플러그인', 'plugin', '{layout-SEQ}', 'plugin/manager/index', '9', 'N', 'N', '{NOW}', '{NOW}');";
$query['menu_item'][] = "INSERT INTO `{$dbprefix}menu_item` (`id`, `menu_id`, `parent_id`, `title`, `url`, `layout`, `target`, `sort`, `hidden`, `index`, `created`, `modified`) VALUES ('{SEQ}', '{menu-SEQ}', '0', '관리자 로그인', 'login', '{layout-SEQ}', 'member/accounts/admin_login', '10', 'Y', 'N', '{NOW}', '{NOW}');";
$query['member_group_admin'][] = "INSERT INTO `{$dbprefix}member_group` (`id`, `title`, `sort`, `admin`, `created`, `modified`) VALUES ('{SEQ}', '최고관리자', '1', 'Y', '{NOW}', '{NOW}');";
$query['member_group'][] = "INSERT INTO `{$dbprefix}member_group` (`id`, `title`, `sort`, `admin`, `created`, `modified`) VALUES ('{SEQ}', '일반회원', '2', 'N', '{NOW}', '{NOW}');";
$query['member'][] = "INSERT INTO `{$dbprefix}member` (`id`, `group`, `name`, `userid`, `passwd`, `created`, `modified`) VALUES ('{SEQ}', '{member_group_admin-SEQ}', '관리자', 'admin', 'UjEBalE1B2ZTPAVw', '{NOW}', '{NOW}');";

