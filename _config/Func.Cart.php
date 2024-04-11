<?

	function FuncAccountAdd() {
		global $dbcon, $ordernum, $ss_u_id;

		FuncAccoutDel($ordernum);

		$SQL = "
			select count(*) from tbl_account
			where
				1=1
				and ordernum = '".$ordernum."'
		";
		$cnt1 = $dbcon -> getCount($SQL);

		$SQL = "
			select count(*) from tbl_account_product
			where
				1=1
				and ordernum = '".$ordernum."'
		";
		$cnt2 = $dbcon -> getCount($SQL);

		if (  $cnt1 > 0 ||  $cnt2 > 0 ) {
			FuncAccoutDel($ordernum);
			alert_page("중복 결제 방지", "cart.php?mode=list");
			exit;
		}

		$SQL = "
			insert into tbl_account
			set
				a_idx = null
				, u_id = '".$ss_u_id."'
				, ordernum = '".$ordernum."'
				, delivery_price = '".$_POST["delivery_price"]."'
				, total_price = '".$_POST["total_price"]."'
				, buy_price = '".$_POST["buy_price"]."'
				, account_type = '".$_POST["account_type"]."'
				, accout_bank = '".$_POST["accout_bank"]."'
				, accout_date = '".$_POST["accout_date"]."'
				, accout_name = '".$_POST["accout_name"]."'
				, buyer_name = '".$_POST["buyer_name"]."'
				, buyer_tel = '".$_POST["buyer_tel"]."'
				, buyer_hp = '".$_POST["buyer_hp"]."'
				, buyer_post = '".$_POST["buyer_post"]."'
				, buyer_addr1 = '".$_POST["buyer_addr1"]."'
				, buyer_addr2 = '".$_POST["buyer_addr2"]."'
				, buyer_email = '".$_POST["buyer_email"]."'
				, buyer_content = '".$_POST["buyer_content"]."'
				, receive_name = '".$_POST["receive_name"]."'
				, receive_tel = '".$_POST["receive_tel"]."'
				, receive_hp = '".$_POST["receive_hp"]."'
				, receive_post = '".$_POST["receive_post"]."'
				, receive_addr1 = '".$_POST["receive_addr1"]."'
				, receive_addr2 = '".$_POST["receive_addr2"]."'
				, a_state = '1'
				, a_regdate = CURRENT_TIMESTAMP
		";
		//echo "SQL : " .$SQL."<BR>";
		if ( !$dbcon -> query($SQL) ) {
			FuncAccoutDel($ordernum);
			alert_page("Account Table DB Insert False", "cart.php?mode=list");
		}

		for ( $i = 0 ; $i < count($_POST["pr_idx"]); $i++ ) {
			$SQL = "
				insert into tbl_account_product
				set
					ap_idx = null
					, ordernum = '".$ordernum."'
					, pr_idx = '".$_POST["pr_idx"][$i]."'
					, pr_img = '".$_POST["pr_img"][$i]."'
					, pr_name = '".$_POST["pr_name"][$i]."'
					, print_option = '".$_POST["print_option"][$i]."'
					, pr_price = '".$_POST["pr_price"][$i]."'
					, goods_num = '".$_POST["goods_num"][$i]."'
					, show_price = '".$_POST["show_price"][$i]."'
					, ap_regdate = CURRENT_TIMESTAMP
			";
			//echo "SQL : " .$SQL."<BR>";
			if ( !$dbcon -> query($SQL) ) {
				FuncAccoutDel($ordernum);
				alert_page("Account Table DB Insert False", "cart.php?mode=list");
			}
		}

		FuncCartDelAll();

	}

	// 결제 테이블 삭제
	function FuncAccoutDel($ordernum) {
		global $dbcon ;
		$SQL = "
			delete from tbl_account
			where
				1=1
				and ordernum = '".$ordernum."'
		";
		$dbcon -> query($SQL);
		$SQL = "
			delete from tbl_account_product
			where
				1=1
				and ordernum = '".$ordernum."'
		";
		$dbcon -> query($SQL);
	}

	// 장바구니 추가
	function FuncCartAdd() {
		global $dbcon, $ordernum, $pr_idx, $goods_num, $pr_option;
		$SQL = "
			select count(*) from tbl_cart
			where
				1=1
				and c_ordernum = '".$ordernum."'
				and c_pr_idx = '".$pr_idx."'
				and c_pr_option = '".$pr_option."'

		";
		$cnt = $dbcon -> getCount($SQL);
//		echo "SQL : ".$SQL."<BR>";
//		echo "cnt : ".$cnt."<BR>";

		// 신규 상품 추가
		if ( $cnt == 0 ) {
			$SQL = "
				insert into tbl_cart
				set
					c_idx = null
					, c_ordernum = '".$ordernum."'
					, c_pr_idx = '".$pr_idx."'
					, c_goods_num  = '".$goods_num."'
					, c_pr_option  = '".$pr_option."'
					, c_regdate = CURRENT_TIMESTAMP
			";
		}

		// 기존 상품있을때 갯수 추가 (갯수 업데이트)
		else {
			$SQL = "
				update tbl_cart
				set
					c_goods_num  = c_goods_num + '".$goods_num."'
					, c_regdate = CURRENT_TIMESTAMP
				where
					1=1
					and c_ordernum = '".$ordernum."'
					and c_pr_idx = '".$pr_idx."'
					and c_pr_option = '".$pr_option."'
			";
		}

		//echo "SQL : ".$SQL."<BR>";

		$rs = $dbcon -> query($SQL);
		return $rs;
	} // end function

	// 장바구니 모두 비우기
	function FuncCartDelAll() {
		global $dbcon, $ordernum;
		$SQL = "
			delete from tbl_cart
			where
				1=1
				and c_ordernum = '".$ordernum."'
		";
		$dbcon -> query($SQL);

	}


?>
<?
/*
// 장바구니 생성
CREATE TABLE `bca_sindoh`.`tbl_cart` (
`c_idx` INT UNSIGNED NOT NULL COMMENT '장바구니 고유번호',
`c_ordernum` CHAR( 18 ) NOT NULL COMMENT '주문번호',
`c_goods_price` INT NOT NULL COMMENT '상품 가격',
`c_goods_num` INT NOT NULL COMMENT '상품 주문수량',
PRIMARY KEY ( `c_idx` ) ,
INDEX ( `c_ordernum` )
) ENGINE = MYISAM;

ALTER TABLE `tbl_cart` CHANGE `c_idx` `c_idx` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '장바구니 고유번호' ;
ALTER TABLE `tbl_cart` CHANGE `c_goods_price` `c_goods_price` INT( 11 ) UNSIGNED NOT NULL COMMENT '상품 가격'
ALTER TABLE `tbl_cart` CHANGE `c_goods_num` `c_goods_num` INT( 11 ) UNSIGNED NOT NULL COMMENT '상품 주문수량'
ALTER TABLE `tbl_cart` CHANGE `c_goods_num` `c_goods_num` INT( 11 ) NOT NULL COMMENT '상품 주문수량'



// 결제 테이블 생성 (상품정보 미포함)
CREATE TABLE `bca_sindoh`.`tbl_account` (

`a_idx` INT NOT NULL AUTO_INCREMENT COMMENT '고유값',
`u_id` varchar(30) not null comment '주문자 아이디',
`ordernum` varchar(18) not null comment '주문번호',
`delivery_price` INT NOT NULL DEFAULT '0' COMMENT '배송료',
`total_price` INT NOT NULL DEFAULT '0' COMMENT '상품 총 합계가격',
`buy_price` INT NOT NULL DEFAULT '0' COMMENT '결제 번호',
`account_type` CHAR( 1 ) NOT NULL DEFAULT '1' COMMENT '결제방식',
`accout_bank` VARCHAR( 255 ) NOT NULL COMMENT '입금은행',
`accout_date` varchar(20) NOT NULL COMMENT '입금예정일',
`accout_name` VARCHAR( 20 ) NOT NULL COMMENT '입금자명',
`buyer_name` VARCHAR( 20 ) NOT NULL COMMENT '주문자 성명',
`buyer_tel` VARCHAR( 15 ) NOT NULL COMMENT '주문자 전화번호',
`buyer_hp` VARCHAR( 15 ) NOT NULL COMMENT '주문자 핸드폰번호',
`buyer_post` VARCHAR( 7 ) NOT NULL COMMENT '주문자 우편번호',
`buyer_addr1` VARCHAR( 100 ) NOT NULL COMMENT '주문자 주소1',
`buyer_addr2` VARCHAR( 100 ) NOT NULL COMMENT '주문자 주소2',
`buyer_email` VARCHAR( 100 ) NOT NULL COMMENT '주문자 E-mail',
`buyer_content` TEXT NOT NULL COMMENT '주문자 남기는말',
`receive_name` VARCHAR( 20 ) NOT NULL COMMENT '배송지 성명',
`receive_tel` VARCHAR( 15 ) NOT NULL COMMENT '배송지 전화번호',
`receive_hp` VARCHAR( 15 ) NOT NULL COMMENT '배송지 핸드폰번호',
`receive_post` VARCHAR( 7 ) NOT NULL COMMENT '배송지 우편번호',
`receive_addr1` VARCHAR( 100 ) NOT NULL COMMENT '배송지 주소1',
`receive_addr2` VARCHAR( 100 ) NOT NULL COMMENT '배송지 주소2',
`a_state` CHAR( 1 ) NOT NULL DEFAULT '1' COMMENT '주문상태',
`a_regdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '주문일',
PRIMARY KEY ( `a_idx` ) ,
INDEX ( `a_regdate` )
) ENGINE = MYISAM COMMENT = '결제 테이블 ( 상품정보 미포함 )'


// 결제 테이블 생성 (상품정보)
CREATE TABLE `bca_sindoh`.`tbl_account_product` (
`ap_idx` INT NOT NULL AUTO_INCREMENT COMMENT '고유값',
`ordernum` VARCHAR( 18 ) NOT NULL COMMENT '주문 번호',
`pr_idx` INT UNSIGNED NOT NULL COMMENT '상품 번호',
`pr_img` VARCHAR( 255 ) NOT NULL COMMENT '상품 이미지',
`pr_name` VARCHAR( 255 ) NOT NULL COMMENT '상품 명',
`pr_price` INT UNSIGNED NOT NULL COMMENT '상품 가격',
`goods_num` INT UNSIGNED NOT NULL COMMENT '주문 갯수',
`show_price` INT UNSIGNED NOT NULL COMMENT '개별 상품 총합',
`ap_regdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '등록일',
PRIMARY KEY ( `ap_idx` ) ,
INDEX ( `ordernum` , `ap_regdate` )
) ENGINE = MYISAM COMMENT = '결제 테이블 ( 상품정보 )'
*/
?>
