<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	if($ss_u_level < 9) {
		alert_back("종합관리자만 접근 가능합니다.");
		exit;
	}
	$tm = "MN3";
	$lm = "";
	include $path_admin."inc/header.php";
?>
<?
// 페이지 설정
$page					= REQSTR($page, 1);
$num_per_page			= REQSTR($num_per_page, 30);
$page_per_block			= REQSTR($page_per_block, 10);
$first					= $num_per_page * ($page - 1);
$last					= $num_per_page * $page;

// 검색설정
$query_where			= "";
$sub_query_where		= "";

#### 검색 설정 Start
$search					= REQSTR($search, "");
$search_text			= REQSTR($search_text, "");

$search_orderby			= REQSTR($search_orderby, "");
$search_sort			= REQSTR($search_sort, "");

if (strlen($search_text) > 0) {
	$query_where .= " and ".$search." = '" . all_seed_enc($search_text) . "'  ";
}


$search_orderby = " seq desc";

$parameter = "&search=" . $search .	"&search_text=" . $search_text;
#### 검색 설정 End

### 쿼리설정
$field				= " * ";
$table				= "tbl_restricted_users";
$where 				= $query_where;
$orderby			= $search_orderby;
$limit				= $first . ", " . $num_per_page;

if (strlen($search_text) > 0) {
$ArrRS				= $dbcon->getList($field, $table, $where, $orderby, $limit);
}
$total_record	= $ArrRS[0];
$result				= $ArrRS[1];
unset($ArrRS);

// 페이지 & 리스트 설정
$total_page		= ceil($total_record / $num_per_page);
$no				= $total_record - $first;


?>
<style>
		/* 스타일 추가 */
		.inline-inputs {
				display: flex;
				align-items: center;
		}
		.inline-inputs input {
				margin-right: 5px;
		}
		.inline-inputs input:last-child {
				margin-right: 0;
		}
</style>
<script type="text/javascript">
		function search_go(){
				return true; // 검색 폼이 제출될 때 실행될 함수
		}
		function addNewEntry() {
				var newRow = `
				<tr id="newEntry">
						<td>신규</td>
						<td>
								<select name="is_restricted[new]">
										<option value="Y">제한</option>
										<option value="N">제한 해제</option>
								</select>
						</td>
						<td><input type="text" name="user_name[new]" value=""></td>
						<td class="inline-inputs">
								<input type="text" name="o_isdn1[new]" value="" maxlength="6">
								<span>-</span>
								<input type="text" name="o_isdn2[new]" value="" maxlength="7">
						</td>
						<td><input type="text" name="note[new]" value=""></td>
						<td>신규 등록일</td>
						<td>신규 수정일</td>
						<td>
								<button type="submit" name="act" value="add">추가</button>
						</td>
				</tr>
				`;
				var tbody = $('#service_tb tbody');
				if (tbody.find('tr').length === 0 || tbody.find('tr:first').find('td').length === 1) {
						tbody.empty(); // tbody 비우기
				}
				tbody.prepend(newRow);
		}
		$(function(){
				$("#addTR").click(function () {
						if ($('#newEntry').length === 0) {
								addNewEntry();
						}
				});
		});
	function checkSubmit() {
		let ff = document.WriteForm;
		let action_type = document.querySelector('input[name="action_type"]');
		let newEntry = document.querySelector('#newEntry');
		if (newEntry) {
			let user_name = newEntry.querySelector('input[name="user_name[new]"]').value.trim();
			let o_isdn1 = newEntry.querySelector('input[name="o_isdn1[new]"]').value.trim();
			let o_isdn2 = newEntry.querySelector('input[name="o_isdn2[new]"]').value.trim();
			let note = newEntry.querySelector('input[name="note[new]"]').value.trim();
			if (!user_name) {
					alert("이름을 입력하여 주십시오.");
					return false;
			}
			if (!o_isdn1) {
					alert("주민번호 앞자리를 입력하여 주십시오.");
					return false;
			}
			if (!o_isdn2) {
					alert("주민번호 뒷자리를 입력하여 주십시오.");
					return false;
			}
			if (!note) {
					alert("메모를 입력하여 주십시오.");
					return false;
			}
			
			action_type.value = 'add';
			
			ff.action="./restricted_users_mod_ok.php?<?=$parameter?>";
			ff.submit();
		} else {
			alert("신규 등록할 데이터가 없습니다.");
		}
	}
	function confirmSave(row_seq) {
		let ff = document.WriteForm;
		let action_type = document.querySelector('input[name="action_type"]');
		let seq = document.querySelector('input[name="seq"]');
    let user_name = document.querySelector(`input[name="user_name[${row_seq}]"]`).value.trim();
    let o_isdn1 = document.querySelector(`input[name="o_isdn1[${row_seq}]"]`).value.trim();
    let o_isdn2 = document.querySelector(`input[name="o_isdn2[${row_seq}]"]`).value.trim();
    let note = document.querySelector(`input[name="note[${row_seq}]"]`).value.trim();
    if (!user_name) {
			alert("이름을 입력하여 주십시오.");
			return false;
		}
         //이름에 * 이 있는지 체크
         if (user_name.includes('*')) {
            alert("이름을 정확히 입력하여 주십시오.");
            return false;
        }
		if (!o_isdn1) {
			alert("주민번호 앞자리를 입력하여 주십시오.");
			return false;
		}
		if (!o_isdn2) {
			alert("주민번호 뒷자리를 입력하여 주십시오.");
			return false;
		}
        //숫자만 입력 됐는지 체크
        if (!/^\d+$/.test(o_isdn2)) {
            alert("주민번호 뒷자리는 숫자만 입력하여 주십시오.");
            return false;
        }



		if (!note) {
			alert("메모를 입력하여 주십시오.");
			return false;
		}
		seq.value = row_seq;
		action_type.value = 'save';
		ff.action="./restricted_users_mod_ok.php?<?=$parameter?>";
		ff.submit();
		return true;
	}
	function confirmDelete(row_seq) {
		if (confirm("정말 삭제하시겠습니까?")) {
			let action_type = document.querySelector('input[name="action_type"]');
			let seq = document.querySelector('input[name="seq"]');
			seq.value = row_seq;
			action_type.value = 'delete';
			document.WriteForm.submit();
		}
	}
</script>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">가입자제한리스트</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<!-- 190725 수정 800px->100% -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
		<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="mode" value="list">
			<input type="hidden" name="category_cd" value="">

			<table class="adm-searchForm">
			<colgroup>
					<col width="8%" />
					<col width="42%" />
					<col width="8%" />
					<col width="42%" />
			</colgroup>
			<tr>
					<th>검색 조건</th>
					<td colspan="3">
							<select name="search">
									<option value="user_name" <?= $search == "user_name" ? "selected" : "" ?>>이름</option>
									<option value="o_isdn1" <?= $search == "o_isdn1" ? "selected" : "" ?>>생년월일</option>
							</select>
							<input type="text" name="search_text" class="w400" value="<?= $search_text ?>" style="margin-left: 10px;">
							<input type="submit" value="검색">
					</td>
			</tr>
			</table>
		</form>

		<form method="post" name="WriteForm" action="restricted_users_mod_ok.php" method="post" enctype="multipart/form-data" onsubmit="return checkSubmit()">
			<input type="hidden" name="action_type" value="">	
			<input type="hidden" name="seq" value="">
			<table class="adm-list-tb" id="service_tb">
				<colgroup>
						<col width="6%" />
						<col width="10%" />
						<col width="10%" />
						<col width="20%" />
						<col width="*" />
						<col width="6%" />
						<col width="6%" />
						<col width="20%" />
				</colgroup>
				<thead>
						<tr>
								<th>NO</th>
								<th>제한여부</th>
								<th>이름</th>
								<th>주민등록번호</th>
								<th>메모</th>
								<th>등록일</th>
								<th>수정일</th>
								<th>관리 <br/><em class="inp_black1"><input type="button" value="입력" id="addTR"></em></th>
						</tr>
				</thead>
				<tbody>
				<? if ($total_record == 0) { ?>
						<tr onClick="view_go()" class="click">
							<td colspan="16"><?= $GLOBALS["msg_list_notdata"] ?></td>
						</tr>
						<?
					} else {
						while ($rows = $dbcon->fetch_array($result)) {
							extract($rows);
							unset($rows);
					?>
					<tr>
							<td><?= $seq ?></td>
							<td>
									<select name="is_restricted[<?= $seq ?>]">
											<option value="Y" <?= $is_restricted == 'Y' ? 'selected' : '' ?>>제한</option>
											<option value="N" <?= $is_restricted == 'N' ? 'selected' : '' ?>>제한 해제</option>
									</select>
							</td>
							<td><input type="text" name="user_name[<?= $seq ?>]" value="<?= maskingKoName(all_seed_dec($user_name)) ?>"></td>
							<td class="inline-inputs">
									<input type="text" name="o_isdn1[<?= $seq ?>]" value="<?= all_seed_dec($o_isdn1) ?>" maxlength="6">
									<span>-</span>
									<input type="text" name="o_isdn2[<?= $seq ?>]" value="<?= maskingIsdn2(all_seed_dec($o_isdn2)) ?>" maxlength="7">
							</td>
							<td><input type="text" name="note[<?= $seq ?>]" value="<?= $note ?>"></td>
							<td><?= substr($regdate, 0, 16) ?></td>
							<td><?= substr($edited_date, 0, 16) ?></td>
							<td>
									<button type="button" name="save" onclick="confirmSave(<?= $seq ?>)">저장</button>
									<button type="button" name="delete" onclick="confirmDelete(<?= $seq ?>)">삭제</button>
							</td>
					</tr>
					<?}
					}
					unset($result);
					?>
				</tbody>
			</table>
		</form>
		<!--  (e) 리스트 영역  -->

		<!-- (s) 페이징 처리 -->
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td align="center" valign="top" style="padding:10px 0 0 0">
						<? list_page($page, $total_page, $page_per_block); ?>
					</td>
				</tr>
			</table>
			<!-- (e) 페이징 처리 -->

		</td>
	</tr>
</table>


<? include $path_admin."inc/footer.php"; ?>
<? $dbcon -> dbcon_close();?>