<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

$parameter = "&search_u_level=".$search_u_level."&search_u_gubun=".$search_u_gubun."&search_u_state=".$search_u_state."&search_u_sex=".$search_u_sex."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page;

if ($idx){
	$SQL = "delete from tb_reserve_tel where idx=".$idx."";
	$result = $dbcon -> query($SQL);
	$tt = "삭제";
}
?>
<script type="text/javascript">
<!--
alert("<?=$tt?>되었습니다");
document.location.href="tel_list.php?<?=$parameter?>";
//-->
</script>