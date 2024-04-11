<?
	header("Content-Type: text/html; charset=UTF-8");
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.Array.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.MSG.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.SQL.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.session.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.DB.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.File.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.global.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.page.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.product.php";


	$dbcon = new dbcon;
	$dbcon -> dbcon_open(0);
	mysql_query("set names utf8");
	$SC_Rows = getSiteConfig();
	extract($SC_Rows);
	unset($SC_Rows);


	#### 스킨정보
	$sc_path_skin_member			= $path_skin_member.$sc_skin_member."/";
	$sc_path_skin_product			= $path_skin_product.$sc_skin_product."/";
	$sc_url_skin_member				= $url_skin_member.$sc_skin_member."/";
	$sc_url_skin_product				= $url_skin_product.$sc_skin_product."/";
	$sc_url_skin_mail					= $url_skin_mail."default/";
?>
<?
	$pc_num = REQSTR($pc_num, "");
?>
<?
	// 카테고리 가져오기
	$SQL = "
		select
			pc_num, pc_name
			, ( select count(pc_num) from tbl_product_category where length(pc_num) = length(A.pc_num) + 2 and SUBSTRING(pc_num, 1, length(A.pc_num) ) = A.pc_num ) as next_pc_num_cnt
		from
			tbl_product_category A
		where
			1=1
			and length(pc_num) = ".(getLen($pc_num)+2)."
			and pc_num like '".$pc_num."%'
		order by
			pc_sort asc
	";
	$rs = $dbcon -> query($SQL) ;
	$dbcon -> dbcon_close();
?>
var Obj_sub = document.all.obj_<?=$pc_num?>_sub;
//alert(Obj_sub.style.display);
<?
	$print_js = '';
	while( $row = $dbcon -> fetch_array( $rs )) {
		$print_js .= "<span style='width:100%;'>";
		for ( $i = 0 ; $i < ( getLen($row[pc_num])/2)-1; $i++ ) {
			$print_js .= "<img src='".$url_product."images/cate_hline.gif' border='0'>";
		}
		if ( $row[next_pc_num_cnt] > 0 ) {
			$print_js .= "<a href=\\\"javascript:menuclick('".$row[pc_num]."');\\\">";
			$print_js .= "<img src='".$url_product."images/cate_plus.gif' border='0' style='cursor:hand;' id='obj_".$row[pc_num]."'></a><img src='".$url_product."images/folder_bullet.gif' border='0'>";
		} else {
			$print_js .= "<img src='".$url_product."images/cate_default.gif'><img src='".$url_product."images/folder_bullet.gif' border='0'>";
		}
		$print_js .= "<a href=\\\"javascript:menuHandler('".$row[pc_num]."','".$row[pc_name]."');\\\"> ".$row[pc_name]." </a>";
		$print_js .= "<span id='obj_".$row[pc_num]."_sub' style='width:100%;display:none'></span>";
		//$print_js .= "<br>";
		$print_js .= "</span>";
	}
?>
Obj_sub.innerHTML += "<?=$print_js?>";
Obj_sub.style.display = "block";
