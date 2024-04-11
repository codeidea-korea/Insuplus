<?
$tbl_user_filed = "
							u_idx, A.u_id, u_pw, u_name, u_jumin1, u_jumin2, u_email1, u_email2, u_email_receipt, u_sms_receipt, u_level, u_gubun, u_state
							, DATE_FORMAT( u_regdate , '%Y-%m-%d' ) as u_regdate
							, u_hp, u_tel, u_fax, u_post, u_addr, u_area, u_homepage, u_sex, u_birth, u_birth_luner
							, ( select count(u_id) from tbl_user_log where u_id = A.u_id ) as u_log_cnt
";
$tbl_user_table = "
							tbl_user A
							left outer join tbl_user_info B
							on A.u_id = B.u_id
";





/*
	회원 테이블
*/
$tbl_create_tbl_user = "
CREATE TABLE tbl_user (
	u_idx int(11) NOT NULL auto_increment,
	u_id varchar(20) NOT NULL,
	u_pw varchar(100) NOT NULL,
	u_name varchar(50) NOT NULL,
	u_jumin1 char(6) default NULL,
	u_jumin2 varchar(100) default NULL,
	u_email1 varchar(100) default NULL,
	u_email2 varchar(100) default '1',
	u_email_receipt int(1) NOT NULL default '1',
	u_sms_receipt int(1) NOT NULL,
	u_level int(1) NOT NULL default '0',
	u_state int(1) NOT NULL default '0',
	u_regdate timestamp NOT NULL default CURRENT_TIMESTAMP,
	PRIMARY KEY  (u_idx),
	UNIQUE KEY u_id_2 (u_id),
	KEY u_id (u_id,u_name,u_state),
	KEY u_regdate (u_regdate)
) ENGINE=MyISAM DEFAULT CHARSET=utf8  ;
";


/*
	회원 상세 정보 테이블
*/

$tbl_create_tbl_user_info = "
CREATE TABLE tbl_user_info (
u_id VARCHAR( 20 ) NOT NULL ,
u_hp VARCHAR( 14 ) NULL ,
u_tel VARCHAR( 14 ) NULL ,
u_fax VARCHAR( 14 ) NULL ,
u_post VARCHAR( 7 ) NULL ,
u_addr VARCHAR( 255 ) NULL ,
u_area VARCHAR( 4 ) NULL ,
u_homepage VARCHAR( 100 ) NULL ,
u_sex CHAR( 1 ) NOT NULL DEFAULT 'M' ,
u_birth TIMESTAMP NULL ,
u_birth_luner INT( 1 ) NOT NULL DEFAULT '0',
PRIMARY KEY ( u_id )
) ENGINE = MYISAM
";

/*
	회원 포인트 테이블
*/

$tbl_create_tbl_user_point = "
CREATE TABLE tbl_user_point (
up_idx INT NOT NULL AUTO_INCREMENT ,
u_id VARCHAR( 20 ) NOT NULL ,
up_gubun VARCHAR( 50 ) NOT NULL ,
up_point INT NOT NULL ,
up_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY ( up_idx ) ,
INDEX ( u_id )
) ENGINE = MYISAM
";

/*
	회원 로그인 log 테이블
*/

$tbl_create_tbl_user_log = "
CREATE TABLE tbl_user_log (
ul INT NOT NULL AUTO_INCREMENT ,
u_id VARCHAR( 20 ) NOT NULL ,
ul_ip VARCHAR( 15 ) NOT NULL ,
ul_regdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY ( ul ) ,
INDEX ( u_id )
) ENGINE = MYISAM
";

/*
	관리자 생성
*/
$tbl_insert_admin = "
INSERT INTO tbl_user (
u_idx ,
u_id ,
u_pw ,
u_name ,
u_jumin1 ,
u_jumin2 ,
u_email1 ,
u_email2 ,
u_email_receipt ,
u_sms_receipt ,
u_level ,
u_gubun ,
u_state ,
u_regdate
)
VALUES (
NULL , 'admin', PASSWORD( '111111' ) , '관리자', '111111', PASSWORD('222222'), 'admin', 'admin.co.kr', '1', '1', '10', '0', '1',
CURRENT_TIMESTAMP
);
";

/*
	게시판 생성
*/
$tbl_insert_tbl_board = "
CREATE TABLE IF NOT EXISTS tbl_board_[boardname] (
	seq int(11) NOT NULL auto_increment,
	seq_sub int(11) NOT NULL default '0',
	seq_level int(11) NOT NULL default '0',
	category int(1) default NULL,
	subject varchar(255) NOT NULL default '',
	content text NOT NULL,
	writer varchar(100) NOT NULL default '',
	passwd varchar(100) NOT NULL,
	nick_name varchar(100) NOT NULL default '',
	email1 varchar(100) default NULL,
	email2 varchar(100) default NULL,
	view_cnt int(11) NOT NULL default '0',
	notice char(1) NOT NULL default 'N',
	imgfile varchar(100) default NULL,
	regdate timestamp NOT NULL default CURRENT_TIMESTAMP,
	secret char(1) NOT NULL default 'N',
	hidden char(1) NOT NULL default 'N',
	ext1 varchar(100) default NULL,
	ext2 varchar(100) default NULL,
	ext3 varchar(100) default NULL,
	ext4 varchar(100) default NULL,
	ext5 varchar(100) default NULL,
	ext6 varchar(100) default NULL,
	ext7 varchar(100) default NULL,
	ext8 varchar(100) default NULL,
	ext9 varchar(100) default NULL,
	ext10 varchar(100) default NULL,
	PRIMARY KEY  (seq),
	KEY subject (subject),
	KEY writer (writer),
	FULLTEXT KEY content (content)
)

";

/*
	게시판 삭제
*/
$tbl_drop_tbl_board = "
	drop table tbl_board_[boardname]
";


/*
	게시판 설정 테이블
*/
$tbl_config_board_list = "
CREATE TABLE config_board_list (
	bc_id VARCHAR( 50 ) NOT NULL ,
	bc_name VARCHAR( 100 ) NOT NULL ,
	bc_skin VARCHAR( 50 ) NOT NULL ,
	bc_login_check CHAR( 1 ) NOT NULL DEFAULT 'N',
	bc_category_use CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_notice_use CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_reply_use CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_comment_use CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_secret_use CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_hidden_use CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_editor_use CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_search_use CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_list_size TINYINT NOT NULL DEFAULT '10',
	bc_page_size TINYINT NOT NULL DEFAULT '10',
	bc_title_size TINYINT NOT NULL DEFAULT '50',
	bc_new_use CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_email_use CHAR( 1 ) NOT NULL DEFAULT 'N',
	bc_homepage_use CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_prev_next CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_state_use CHAR( 1 ) NOT NULL DEFAULT 'Y',
	bc_upfile_image CHAR( 1 ) NOT NULL DEFAULT 'N',
	bc_upfile_image_width int NOT NULL DEFAULT '100',
	bc_upfile_image_height int NOT NULL DEFAULT '100',
	bc_upfile_flash CHAR( 1 ) NOT NULL DEFAULT 'N',
	bc_upfile_flash_width int NOT NULL DEFAULT '300',
	bc_upfile_flash_height int NOT NULL DEFAULT '200',
	bc_upfile_media CHAR( 1 ) NOT NULL ,
	bc_upfile_media_width int NOT NULL DEFAULT '300',
	bc_upfile_media_height int NOT NULL DEFAULT '200',
	bc_upfile_cnt TINYINT NOT NULL DEFAULT '10',
	bc_upfile_size TINYINT NOT NULL DEFAULT '10',
	bc_upfile_ext_upload VARCHAR( 255 ) NOT NULL DEFAULT 'asp,php,php3,php4,php5,html,htm,js,css',
	bc_upfile_ext_download VARCHAR( 255 ) NOT NULL DEFAULT 'asp,php,php3,php4,php5,html,htm,js,css',
	bc_top_include VARCHAR( 255 ) NULL ,
	bc_top_html TEXT NULL ,
	bc_bottom_include VARCHAR( 255 ) NULL ,
	bc_bottom_html TEXT NULL ,
	bc_auth_view TINYINT NOT NULL DEFAULT '0',
	bc_auth_write TINYINT NOT NULL DEFAULT '0',
	bc_auth_modify TINYINT NOT NULL DEFAULT '0',
	bc_auth_delete TINYINT NOT NULL DEFAULT '0',
	bc_auth_notice TINYINT NOT NULL DEFAULT '0',
	bc_auth_reply TINYINT NOT NULL DEFAULT '0',
	bc_auth_comment TINYINT NOT NULL DEFAULT '0',
	bc_auth_secret TINYINT NOT NULL DEFAULT '0',
	bc_auth_hidden TINYINT NOT NULL DEFAULT '0',
	bc_auth_upload TINYINT NOT NULL DEFAULT '0',
	bc_auth_download TINYINT NOT NULL DEFAULT '0',
	bc_regdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	PRIMARY KEY ( bc_id )
)
";


$tbl_config_board_list_delete = "
	delete from config_board_list
	where bc_id = '[boardname]'
";



/*
게시판 설정 모두 바꿈
UPDATE `bca_astory`.`config_board_list` SET `bc_editor_use` = 'Y',
`bc_upfile_cnt` = '2',
`bc_auth_write` = '7',
`bc_auth_modify` = '7',
`bc_auth_delete` = '7',
`bc_auth_notice` = '7',
`bc_auth_reply` = '7',
`bc_auth_comment` = '7',
`bc_auth_secret` = '7',
`bc_auth_hidden` = '7',
`bc_auth_upload` = '7'


게시판 설정 변경 쿼리
UPDATE `bca_astory`.`config_board_list` SET `bc_editor_use` = 'Y',
`bc_upfile_cnt` = '2',
`bc_auth_write` = '7',
`bc_auth_modify` = '7',
`bc_auth_delete` = '7',
`bc_auth_notice` = '7',
`bc_auth_reply` = '7',
`bc_auth_comment` = '7',
`bc_auth_secret` = '7',
`bc_auth_hidden` = '7',
`bc_auth_upload` = '7' WHERE CONVERT( `config_board_list`.`bc_id` USING utf8 ) = 'bbs_0901' LIMIT 1 ;

*/
?>