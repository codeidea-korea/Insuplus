<? if ($client_mode=="Y"){//사용자
	if($customer_password != '') {
		if($_SESSION["ss_view_seq"] != $seq) { //비밀번호 인증 체크
			alert_page("올바른 경로로 이용해 주세요.","qa_list.php");
		}
	}
	
?>
<div class="col-md-10 col-sm-9">
		<div class="sub-content cs-wrap">
			<div class='row-border responsive'>
				<div class='detail-col-label'>제목</div>
				<div class='detail-col-input colspan-3'><?=$subject?></div>
				<div class='detail-col-label'>상품명</div>
				<div class='detail-col-input'><?=print_pr_name($pr_cd)?></div>
				<div class='detail-col-label'>작성자</div>
				<div class='detail-col-input'><?=all_seed_dec($name)?></div>
				<div class='detail-col-label'>연락처</div>
				<div class='detail-col-input'><?=all_seed_dec($mobile)?></div>
				<div class='detail-col-label'>이메일</div>
				<div class='detail-col-input'><?=all_seed_dec($email1)?>@<?=all_seed_dec($email2)?></div>
				<div class='detail-col-label valign-middle hidden-xs'><p>문의내용</p></div>
				<div class='detail-col-input colspan-3 flex-100 flex-column flex-none p-y-2'>
					<?=nl2br($customer_content)?>
				</div>

			</div>
			<? if($status == "A") { //답변일때 노출?>
			<h4 class='text-black m-t-3 m-b-1'>답변</h4>
			<div class='row-border'>
				<div class='detail-col-label hidden-xs'>답변내용</div>
				<div class='detail-col-input colspan-3 flex-100 flex-column flex-none'>
					<?=$content?>
				</div>
			</div>
			<? } ?>
			<div class='row m-t-3'>
				<div class='col-md-2 col-sm-3 col-xs-3'>
					<a href="javascript:;" onClick="frontList()" class='btn btn-block btn-theme-dark light'>목록</a>
				</div>
				<? if($status != "A") { ?>
				<div class='col-md-2 col-sm-3 col-xs-3 col-md-offset-6 col-sm-offset-3'>
					<a href="javascript:;" onClick="frontDelGo('<?=$seq;?>')" on class='btn btn-block btn-theme-dark'>삭제</a>
				</div>
				<div class='col-md-2 col-sm-3 col-xs-6'>
					<a href="javascript:;" onClick="frontMod('<?=$seq?>')" class='btn btn-block btn-theme-bg'>수정</a>
				</div>
				<? } ?>
			</div>
		</div>
	</div>
</div>
<? }else{ //관리자?>
<script type="text/javascript">
<!--
$( document ).ready( function() {
mod_go('<?=$seq?>');
});
//-->
</script>
<?}?>