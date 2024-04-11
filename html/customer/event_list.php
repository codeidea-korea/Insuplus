<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
	
	$lm = "0105"; //레프트 메뉴 활성화
?>
		<div class="breadcrumb-image">
			<div class="container">
				<h2>이벤트</h2>
				<!-- <h4>빠른 시일 안에 답변 드리겠습니다.</h4>-->
			</div>
		</div>
		<div class="breadcrumb-wrap">
            <div class="container">
				<ol class="breadcrumb">
					<li><a href="../main/index.php">InsuPlus HOME</a></li>
					<li><a href="../customer/cs_center.php">고객센터</a></li>
					<li>이벤트</li>
				</ol>
            </div>
        </div>
		<div class="overflow-hidden">
			<div class="container">
				<div class="row">
					<div class="col-md-2 col-sm-3">
						<?php
							include '../_include/_cs_left.html';
						?>
					</div>
					<?
						
						$bc_id = "event";									// 생성된 게시판 ID 실제로 테이블은 tbl_board_notice
						$client_mode = "Y";								// 게시판스킨에서 Client_mode = Y 에 해당되는 스킨을 가져다 쓰게 된다.
						include_once $path_board."board.php";	// 게시판사용
						$dbcon -> dbcon_close();					// 게시판 DB종료
					?>
					</div>
				</div>
			</div>
        </div>
<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>
<script>
function frontSearch() { //검색
	ff = document.SearchForm;
	ff.submit();
}

function frontList() { //리스트 페이지 이동
	location.href = "<?=$bc_id?>_list.php?mode=list&page=<?=$page?><?=$parameter?>";
}

function frontTab(pr_cd) {
	ff = document.SearchForm;
	ff.pr_cd.value = pr_cd;
	ff.submit();
}

function frontViewGO(seq) {	
	location.href = "<?=$bc_id?>_list.php?mode=view&seq="+seq+"&page=<?=$page?><?=$parameter?>";
}

function frontDelGo(seq) {
	if (confirm("정말로 삭제 하시겠습니까?\n삭제한 정보는 복구가 불가능합니다.")) {
		location.href = "?mode=del_ok&seq="+seq+"<?=$parameter?>";
	}
}

function frontMod(seq) {
	location.href = "?mode=mod&seq="+seq+"&page=<?=$page?><?=$parameter?>";
}

function frontWriteGO() {
	ff = document.WriteForm;

	if (!ff.subject.value) {
		alert("제목을 입력하여 주십시오.");
		ff.subject.focus();
		return false;
	}

	if (!ff.pr_cd.value) {
		alert("상품명을 선택해 주세요.");
		ff.pr_cd.focus();
		return false;
	}

	if (!ff.name.value) {
		alert("이름을 입력해 주세요.");
		ff.name.focus();
		return false;
	}

	if (!ff.mobile.value) {
		alert("연락처를 입력해 주세요.");
		ff.mobile.focus();
		return false;
	}

	if (!ff.email1.value) {
		alert("이메일(1)을 입력해 주세요.");
		ff.email1.focus();
		return false;
	}

	if (!ff.email2.value) {
		alert("이메일(2)을 입력해 주세요.");
		ff.email2.focus();
		return false;
	}

	if (!ff.email2.value) {
		alert("이메일(2)을 입력해 주세요.");
		ff.email2.focus();
		return false;
	}

	if (!ff.customer_content.value) {
		alert("문의내용을 입력해 주세요.");
		ff.customer_content.focus();
		return false;
	}

	if (!ff.customer_password.value) {
		alert("비밀번호를 입력해 주세요.");
		ff.customer_password.focus();
		return false;
	}

	<? if ( $mode == "mod" ) { ?>
		ff.mode.value = "mod_ok";
	<? } elseif ( $mode == "write" ) { ?>
	
		if(!$("#agree_privacy").is(":checked")) {
			alert("개인정보 이용 및 수집에 동의해 주세요.");
			ff.agree_privacy.focus();
			return false;
		}
	
		ff.status.value = "W";
		ff.mode.value = "write_ok";
	<? } ?>
	ff.action = "<?=$PHP_SELF?>";
	//ff.target = "board_iframe";
	ff.submit();
}
</script>

