
<script>
	function CommentNextGo_Client () {
		ff = document.CommentForm;

		//CommentIframe = document.getElementById("CommentIframe");
		//CommentIframe.location.href= "aa";
		ff.target = "CommentIframe";

//		return false;
		if (ff.cmt_u_name.value == "") {
			alert("이름을 입력하세요.");
			ff.cmt_u_name.focus();
			return;
		}

		<? if ( strlen($ss_u_id) == 0 ) { ?>
		if (ff.cmt_u_pw.value == "") {
			alert("비밀번호를 입력하세요.");
			ff.cmt_u_pw.focus();
			return;
		}
		<? } ?>

		if (ff.cmt_content.value == "") {
			alert("내용을 입력하세요.");
			ff.cmt_content.focus();
			return;
		}

		if (ff.mode.value == "insert") {
			ff.action = "<?=$url_comment?>ProcWrite.php";
		}
		else if (ff.mode.value == "modify") {
			ff.action = "<?=$url_comment?>ProcModify.php";
		}
		else {
			return;
		}
		ff.submit();

	}

	function CommentNextGo () {
		ff = document.CommentForm;

		//CommentIframe = document.getElementById("CommentIframe");
		//CommentIframe.location.href= "aa";
		ff.target = "CommentIframe";

//		return false;
		if (ff.cmt_u_name.value == "") {
			alert("이름을 입력하세요.");
			ff.cmt_u_name.focus();
			return false;
		}

		<? if ( strlen($ss_u_id) == 0 ) { ?>
		if (ff.cmt_u_pw.value == "") {
			alert("비밀번호를 입력하세요.");
			ff.cmt_u_pw.focus();
			return false;
		}
		<? } ?>

		if (ff.cmt_content.value == "") {
			alert("내용을 입력하세요.");
			ff.cmt_content.focus();
			return false;
		}

		if (ff.mode.value == "insert") {
			ff.action = "<?=$url_comment?>ProcWrite.php";
		}
		else if (ff.mode.value == "modify") {
			ff.action = "<?=$url_comment?>ProcModify.php";
		}
		else {
			return false;
		}

	}

	function CommentDeleteView(idx,e) {
		if ( confirm("정말로 삭제하시겠습니까?") ) {
			ff = document.CommentDeleteForm;
			<? if ( ($cmt_u_id == $ss_u_id && strlen($ss_u_id) > 0 ) || ( $ss_u_level >= $auth_admin && strlen($ss_u_id) > 0) ) { ?>
				ff.idx.value = idx;
				ff.cmt_u_pw.value = "";
				ff.target = "CommentIframe";
				ff.action = "<?=$url_comment?>ProcDelete.php";
				CommentLayerHidden();
				ff.submit();

			<? } else { ?>
				CommentLayerView();
				ff.idx.value = idx;
				ff.cmt_u_pw.value = "";
				ff.cmt_u_pw.focus();
			<? } ?>
		}

	}

	function CommentLayerView() {

		x = event.screenX;
		y = event.screenY;
		x = (document.layers) ? e.pageX : document.body.scrollLeft+event.clientX;
		y = (document.layers) ? e.pageY : document.body.scrollTop+event.clientY;
//		alert(event.screenX);
//		alert(event.screenY);
//		alert(document.all.CommentLayer.style.width);
//		alert(document.all.CommentLayer.style.height);
//		document.all.CommentLayer.style.left = event.screenX - 200 + 'px';
//		document.all.CommentLayer.style.top = event.screenY - 150 + 'px';
		document.all.CommentLayer.style.left = x - parseInt(document.all.CommentLayer.style.width);
		document.all.CommentLayer.style.top = y - parseInt(document.all.CommentLayer.style.height);
		document.all.CommentLayer.style.display="";
	}

	function CommentLayerHidden() {
		document.all.CommentLayer.style.display="none";
	}

	function CommentDeleteGo() {
		ff = document.CommentDeleteForm;
		if (ff.cmt_u_pw.value == "") {
			alert("비밀번호를 입력하여 주십시오.");
			ff.cmt_u_pw.focus();
			return;
		}

		ff.target = "CommentIframe";
		ff.action = "<?=$url_comment?>ProcDelete.php";
		ff.submit();
		CommentLayerHidden();

	}

	function CommentModifyView(idx) {
		CommentIframe = document.getElementById("CommentIframe");
		CommentIframe.location.href= "<?=$url_comment?>ProcModifyView.php?idx="+idx;
	}

</script>
