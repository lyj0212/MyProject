SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `bbs`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `{dbprefix}bbs` (
  `id` bigint(11) unsigned NOT NULL COMMENT '고유번호',
  `tableid` varchar(100) NOT NULL COMMENT '게시판 아이디',
  `ismember` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '회원여부 및 회원번호',
  `category` bigint(11) unsigned DEFAULT NULL COMMENT '카테고리',
  `projectid` bigint(11) unsigned DEFAULT NULL,
  `fid` int(10) NOT NULL COMMENT '정렬값',
  `thread` varchar(50) NOT NULL DEFAULT 'AA' COMMENT '정렬 및 깊이',
  `subject` varchar(200) NOT NULL COMMENT '제목',
  `contents` longtext COMMENT '내용',
  `name` varchar(20) NOT NULL COMMENT '작성자',
  `passwd` varchar(100) DEFAULT NULL COMMENT '비밀번호',
  `isnotice` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '공지사항 여부',
  `issecret` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '비밀글 여부',
  `ismobile` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '모바일에서 작성 여부',
  `hit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '조회수',
  `total_comment` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '댓글갯수',
  `ip` char(15) NOT NULL COMMENT '아이피',
  `created` datetime NOT NULL COMMENT '작성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`,`thread`),
  KEY `tableid` (`tableid`,`isnotice`),
  KEY `category` (`category`),
  KEY `ismember` (`ismember`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bbs_category`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `{dbprefix}bbs_category` (
  `id` bigint(11) unsigned NOT NULL COMMENT '자동 증가값',
  `tableid` varchar(100) NOT NULL COMMENT '게시판 아이디',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '상위 카테고리 번호',
  `title` varchar(150) NOT NULL COMMENT '제목',
  `sort` smallint(3) unsigned NOT NULL COMMENT '정렬값',
  `created` datetime NOT NULL COMMENT '생성일',
  `modified` datetime NOT NULL COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `tableid` (`tableid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `bbs_setup`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `{dbprefix}bbs_setup` (
  `id` bigint(11) unsigned NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
