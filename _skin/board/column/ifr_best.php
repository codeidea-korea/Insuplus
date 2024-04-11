<?	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel="stylesheet" type="text/css" href="/css/base.css" />
	<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="/js/common_best.js"></script>
	<script type="text/javascript" src="/js/jquery.slides.min.js"></script>
	<script type="text/javascript" src="/js/ecaso.js"></script>
	<script type="text/javascript" src="/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="/js/swfobject.js"></script>





<?
	$bc_id="column";
	$SQLTEMP0 = "
		select seq, subject, regdate, secret, ext1, content, writer, nick_name, (select cate_name from tbl_category where category='board' and bc_id = '".$bc_id."' and idx = A.category ) as cate_name
		from tbl_board_".$bc_id." A
		where
			ext3 = 'Y'
		order by seq desc
		limit 0, 4
	";
	$RSTEMP0 = $dbcon -> query($SQLTEMP0);
?>
<style>
.community {overflow:hidden;}
.best {
	float:left;
	width:305px;
	min-height:646px;
	background-color:#fcfcfc;
	border:0px solid #e7e7e7;
}
.body{
	margin-top:0px;
}
.best #best_content table{
	position:absolute;
	margin-top:30px;
	left:0px;
}
.best .btn {
	position:absolute;
	top:8px;
	left:240px;
	float:right;
}
.best .btn > li {
	float:left;
	margin-left:7px;
}
.best .btn > li:first-child {margin-left:0;}
.best .btn > li > a {
	overflow:hidden;
	display:inline-block;
	width:10px;
	height:10px;
}
.best .btn > li.on > a img,
.best .btn > li > a:hover img {margin-top:-10px;}
</style>

							<div class="best">

								<h4 class="titleTy01" style="float:left;margin-left:-15px;margin-top:-20px;">BEST</h4>

								<div id="best_content">

<?
$k = 0;
while($ROWSTEMP = $dbcon -> fetch_array($RSTEMP0)){
	$k++;
	// 의료진 정보 검색 - 정보에 ID를 검출하여 검색
	$arr_doctor_detail = explode("@",$ROWSTEMP[writer]);
	// 의료진 사진 가져오기
	$mem_dt = getMemberInfo("u_id",$arr_doctor_detail[0]);
	$main_regdate = date('Y.m.d', strtotime($ROWSTEMP[regdate]) );
	if ($mem_dt["u_image"]){
		$col_img = "/_data/member/".$mem_dt["u_image"];
	}else{
		$col_img = "/images/board/test_img.jpg";
	}
	if ($ROWSTEMP[ext1]){
		$sub_content = $ROWSTEMP[ext1];
	}else{
		$sub_content = getStrCut(strip_tags($ROWSTEMP[content]),120,"..");
	}
?>
								<table width="305" cellspacing="0" cellpadding="0" style="margin-top:40px;margin-left:0px;">
								<tr>
									<td class="img"><img src="<?=$col_img?>" alt="<?=$ROWSTEMP[nick_name]?>원장" style="width:303px;height:208px;"/>
									<strong style="width:305px;"><?=$ROWSTEMP[nick_name]?> 원장</strong>
									</td>
								</tr>
								<tr>
									<td  class="txt" style="padding-top:20px;">
									<p class="iconSt03" ><?=$ROWSTEMP["cate_name"]?></p><br/>
									<a href="/html/community/column.php?mode=view&seq=<?=$ROWSTEMP[seq]?>" style="width:303px;" target="_top">
										<strong style="width:305px;"><?=$ROWSTEMP[subject]?><span> </span> <img src="/images/common/icon/icon_h.gif" alt="h" /></strong>
										<em style="width:305px;"><?=$sub_content?></em>									</a>
										<span style="width:305px;"><em class="bullTy08"><?=$main_regdate?></em> <!-- <img src="/images/common/icon/icon_window.gif" alt="새창" onclick="window.open('?mode=view&seq=<?=$ROWSTEMP[seq]?>')"/> --></span>
									</td>
								</tr>
								</table>
<?}?>
</div>
								<ul class="btn">
								<?for ($kk=0;$kk<$k;$kk++){?>
								<li <?if ($kk==0){?>class="on"<?}?>><a href="javascript:;"><img src="/images/common/btn/btn_roll01.png" alt="btn01" /></a></li>
								<?}?>
								</ul>
							</div>

							<script type="text/javascript">
								$('.best').mainScript({
										type : 'slide',
										autoPlay : true,
										contList: $('#best_content > table'),
										navList: $('.best > ul > li')
								});
							</script>