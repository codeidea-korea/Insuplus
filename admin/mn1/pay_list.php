<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out); // 관리자 체크

$tm = "MN1";
$lm = "";
include $path_admin . "inc/header.php";
?>
<?
//	$dbcon -> setDebug(1);
$page_btn_prev = ">>"; // > 버튼
$page_btn_next = "<<"; // < 버튼

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
$pr_name				= REQSTR($pr_name, "");
$ins_name				= REQSTR($ins_name, ""); 
$plan_name				= REQSTR($plan_name, "");
$chk_service			= REQSTR($chk_service, "");
$join_ch				= REQSTR($join_ch, "");
$group_join_type		= REQSTR($group_join_type, "");
$client_id				= REQSTR($client_id, "");
$o_name_b2b				= REQSTR($o_name_b2b, "");
$o_name_en_b2b			= REQSTR($o_name_en_b2b, "");

$search					= REQSTR($search, "");
$search_text			= REQSTR($search_text, "");

$search_orderby			= REQSTR($search_orderby, "");
$search_sort			= REQSTR($search_sort, "");

$search_date_txt		= REQSTR($search_date_txt, "");
$search_date_s			= REQSTR($search_date_s, "");
$search_date_e			= REQSTR($search_date_e, "");

// 2023-11-08 added
$category_cd      = REQSTR($category_cd  , "");
$ins_plan_name    = REQSTR($ins_plan_name, "");

if (strlen($pr_name) > 0) $query_where .= " and pr_name = '" . $pr_name . "' ";
if (strlen($ins_name) > 0) $query_where .= " and ins_name = '" . $ins_name . "' ";
if (strlen($plan_name) > 0) $query_where .= " and plan_name = '" . $plan_name . "' ";
if (strlen($chk_service) > 0) $query_where .= " and chk_service = '" . $chk_service . "' ";
if (strlen($order_step) > 0) $query_where .= " and order_step = '" . $order_step . "' ";

// 2023-11-08 added
if (strlen($category_cd) > 0) $query_where .= " and A.pr_cd in (select product_seq from tbl_board_product_category where category_code = '" . $category_cd . "') ";
if (strlen($ins_plan_name) > 0) $query_where .= " and A.plan_cd in (select seq from tbl_board_plan where ins_plan_name like '%" . $ins_plan_name . "%') ";

if (strlen($search_text) > 0) {
	if ($search == "orderno") {
		$query_where .= " and A.orderno = '" . $search_text . "'  ";
	} else {
		$query_where .= " and A.orderno in (select orderno from tbl_order_list_join where " . $search . " ='" . all_seed_enc($search_text) . "' )  ";
	}
}

if (strlen($join_ch) > 0) $query_where .= "and C.partnership_name like '%" . $join_ch . "%'";
if (is_null($group_join_type) || $group_join_type === "B2C") {
	$query_where .= "and (B.group_join_type is null or B.group_join_type = 'B2C')";
} else if ($group_join_type === "B2B") {
	$query_where .= "and B.group_join_type = 'B2B'";
}
if (strlen($client_id) > 0) $query_where .= "and 1 = (select COUNT(*) from tbl_order_group_join_list where group_join_id = A.group_join_id and client_id = " . $client_id . ")";

if (strlen($search_date_s) > 0) {
  if($search_date_txt == 'A.s_date' || $search_date_txt == 'A.e_date') {
    $query_where .= " and " . $search_date_txt . " >= '" . $search_date_s . "' ";
  } else {
    $query_where .= " and " . $search_date_txt . " >= '" . $search_date_s . " 00:00:00' ";
  }
}
if (strlen($search_date_e) > 0) {
  if($search_date_txt == 'A.s_date' || $search_date_txt == 'A.e_date') {
    $query_where .= " and " . $search_date_txt . " <= '" . $search_date_e . "' ";
  } else {
    $query_where .= " and " . $search_date_txt . " <= '" . $search_date_e . " 23:59:59' ";
  }
}

if($search_date_txt == 'A.s_date' || $search_date_txt == 'A.e_date') {
  $search_orderby = $search_date_txt . " asc, " . $search_date_txt . "_time asc";
} else {
  $search_orderby = " A.seq desc";
}

$parameter = "&pr_name=" . $pr_name . "&ins_name=" . $ins_name . "&plan_name=" . $plan_name . "&chk_service=" . $chk_service . "&search=" . $search .
	"&search_text=" . $search_text . "&search_orderby=" . $search_orderby . "&search_sort=" . $search_sort . "&num_per_page=" . $num_per_page .
	"&order_step=" . $order_step . "&search_date_txt=" . $search_date_txt . "&search_date_s=" . $search_date_s . "&search_date_e=" . $search_date_e .
	"&join_ch=" . $join_ch . "&group_join_type=" . $group_join_type . "&client_id=" . $client_id . "&o_name_b2b=" . $o_name_b2b . 
	"&o_name_en_b2b=" . $o_name_en_b2b . "&category_depth0=" . $category_depth0 . "&category_depth1=" . $category_depth1 . 
  "&category_depth2=" . $category_depth2 . "&category_depth3=" . $category_depth3 . "&ins_plan_name=" . $ins_plan_name . "&category_cd=" . $category_cd;

#### 검색 설정 End

// 쿼리설정
$field	= " A.seq
								,  A.orderno
								,  A.pr_name
								,  A.pr_cd
								,  A.ins_name
								,  A.ins_cd
								,  A.plan_name
								,  A.plan_cd
								,  A.agree_cd
								,  A.service_name
								,  A.service_cd
								,  A.rule_site_cd
								,  A.rule_group_cd
								,  A.rule_privacy_cd
								,  A.ins_file_cd
								,  A.service_file_cd
								,  A.s_date
								,  A.s_date_time
								,  A.e_date
								,  A.e_date_time
								,  A.ins_period
								,  A.chk_p
								,  A.chk_service
								,  A.o_name
								,  A.o_email1
								,  A.o_email2
								,  A.join_cnt
								,  A.join_ch
								,  A.purpose
								,  A.join_nation_cd
								,  A.join_nation_name
								,  A.sale_gubun
								,  A.sale_discount
								,  A.cp_cd
								,  A.recommend_cd
								,  A.order_step
								,  A.ins_amount
								,  A.service_amount
								,  A.s_amount
								,  A.cp_amount
								,  A.vat_amount
								,  A.t_amount
								,  A.cancle_vat_amount
								,  A.cancle_amount
								,  A.writedate
								,  A.cancle_date
								,  A.refund_date
								,  A.refund_file
								,  A.refund_i_amount
								,  A.refund_s_amount
								,  A.pg_id
								,  A.pg_pay_type
								,  A.pg_isdn
								,  A.pay_name
								,  A.pg_in_date
								,  A.o_memo
								,  A.cancle_con
								,  A.group_join_id
								, B.group_join_type
								, D.guarantee1_ins_seq
								, (select partnership_name from tbl_board_partner C where C.seq = A.join_ch) as partnership_name";
$table			= " tbl_order_list A left join tbl_order_list_join B ON (A.orderno=B.orderno and A.o_name = B.o_name and B.chk_join = 'N') left join tbl_board_plan D on A.plan_cd = D.seq";
$where			= $query_where;
$orderby      = $search_orderby;
$limit				= $first . ", " . $num_per_page;

// 20250316 yjhzzzzdev - 조건문 없으면 검색 X 
$ArrRS =[];
if(isset($where) && $where) {
    $ArrRS			= $dbcon->getList($field, $table, $where, $orderby, $limit);
}


$total_record	= $ArrRS[0];
$result			= $ArrRS[1];
unset($ArrRS);

// 페이지 & 리스트 설정
$total_page		= ceil($total_record / $num_per_page);
$no				= $total_record - $first;

// 상품 코드 불러오기
$SQL_PR = "select seq,subject from tbl_board_product  ";
$RS_PR = $dbcon->query($SQL_PR);
// 보험사 불러오기
$SQL_Ins = "select seq,subject from tbl_board_ins_list  ";
$RS_Ins = $dbcon->query($SQL_Ins);
//$dbcon -> dbcon_close();
?>
<script type="text/javascript">
	$(document).ready(function() {
				var clareCalendar = {
					<?= $calendar_opt ?>
				});
</script>
<script type="text/javascript" src="<?= $url_admin ?>js/block.js"></script>
<script>
function view_go(n) {
		location.href = "pay_view.php?orderno=" + n + "<?= $parameter ?>";
	}

	function excel_go() {
        $('#popupOverlay').fadeIn();
        $('#popup').fadeIn();
	}

	function excelReason(name){
      var reason = $('#reason').val().trim();
      var excel_enc = $('#excel_enc').val();

          if (!reason) {
              alert('사유를 입력해 주세요.');
              return;
          }

          // Ajax로 사유 저장
          $.ajax({
              type: 'POST',
              url: '../ajax_excel_reason.php',
              data: { program: "결제내역엑셀", reason: reason, excel_enc: excel_enc },
              dataType: 'json',
              success: function (response) {
                  if (response.success) {
                      location.href = "excel_payhistory.php?mode=excel&excel_enc="+excel_enc+"&<?= $GLOBALS["parameter"] ?>";
                      $('#popupOverlay').fadeOut();
                      $('#popup').fadeOut();
                  } else {
                      alert('사유 저장 실패! 다시 시도해 주세요.');
                  }
              },
              error: function () {
                  alert('서버 오류가 발생했습니다.');
              }
          });
          $("#excel_type").val('');
          $("#reason").val('');
      }


	function pop_client() {
		var popPlan = window.open('popup_get_flying_client.php', 'popPlan', 'top=0,left=0, width=1155,height=765');
		popPlan.focus();
	}
</script>

<?  $PRD_CAT = getProductCatetories(); ?>
<script>
///////////////////////////////////////////////////////////////////////////////
// 2023-11-08 added by kyle
///////////////////////////////////////////////////////////////////////////////
const PAGE_PRD_CAT = JSON.parse('<?= json_encode($PRD_CAT) ?>');

function generate_select(selector, list){
  let html=[];
	let title = document.querySelector(selector).getAttribute('placeholder');

  if (Array.isArray(list)){
    html = list.map(item => `<option value="${item.category_code}">${item.category_name}</option>`);
  }

  html.unshift(`<option value="">${title}</option>`);
  document.querySelector(selector).innerHTML = html.join('');
}

function init_depts(){
  let categories = PAGE_PRD_CAT.filter(item => item.depth === '0');

  generate_select('#category_depth0', categories);
}

function change_depths(event){
  const val = event.currentTarget.value;
  const {textContent} = [].find.call(event.currentTarget.children, (item) => item.selected);
	const depth = Number(event.currentTarget.dataset.depth);
  const categories = PAGE_PRD_CAT.filter(item => item.parent_code === val);

  if (depth !== 3) generate_select(`#category_depth${depth+1}`, categories);
	
	switch (depth) {
		case 0:
			generate_select('#category_depth2', null);
		case 1:
			generate_select('#category_depth3', null);
	}

  document.querySelector('input[name=category_cd]').value = document.querySelector('#category_depth3').value
      || document.querySelector('#category_depth2').value
      || document.querySelector('#category_depth1').value
      || document.querySelector('#category_depth0').value;
}

window.addEventListener('load', ()=>{
  const depth0 = '<?= $category_depth0 ?>';
  const depth1 = '<?= $category_depth1 ?>';
  const depth2 = '<?= $category_depth2 ?>';
  const depth3 = '<?= $category_depth3 ?>';
  const depths = [depth0, depth1, depth2, depth3];
  init_depts();

  document.querySelector('#category_depth0').addEventListener('change', change_depths);
  document.querySelector('#category_depth1').addEventListener('change', change_depths);
  document.querySelector('#category_depth2').addEventListener('change', change_depths);
  document.querySelector('#category_depth3').addEventListener('change', change_depths);

  depths.forEach((d,i)=>{
    if (d){
      document.querySelector(`#category_depth${i}`).value = d;
      document.querySelector(`#category_depth${i}`).dispatchEvent(new Event('change'));
    }
  })
});

///////////////////////////////////////////////////////////////////////////////
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?= $url_admin ?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">결제내역</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0" class="no-drag">
	<tr>
		<td>
			<!-- (s) 검색영역  -->
			<form name="frm_group_join" method="get" action="<?= $PHP_SELF ?>" onsubmit="return search_go()" autocomplete="off">
				<input type="hidden" name="bc_id" value="<?= $bc_id ?>">
				<input type="hidden" name="mode" value="list">
				<input type="hidden" name="search_category" value="<?= $search_category ?>">
				<input type="hidden" name="client_id" value="<?= $client_id ?>">
				<input type="hidden" name="biz_num" value="">
				<input type="hidden" name="grp_cd" value="">
        <input type="hidden" name="category_cd" value="">
				<table class="adm-searchForm">
					<colgroup>
						<col width="5%" />
						<col width="45%" />
						<col width="5%" />
						<col width="45%" />
					</colgroup>
          <tr>
            <th>카테고리</th>
            <td>
							<select name="category_depth0" id="category_depth0" data-depth="0" placeholder="지역 선택">
								<option value="">지역 선택</option>
							</select>
							<select name="category_depth1" id="category_depth1" data-depth="1" placeholder="구분1 선택">
								<option value="">구분1 선택</option>
							</select>
							<select name="category_depth2" id="category_depth2" data-depth="2" placeholder="구분2 선택">
								<option value="">구분2 선택</option>
							</select>
							<select name="category_depth3" id="category_depth3" data-depth="3" placeholder="구분3 선택">
								<option value="">구분3 선택</option>
							</select>
            </td>
            <th>플랜명</th>
            <td>
              <input type="text" name="ins_plan_name" class="w150" value="<?= $ins_plan_name ?>" placeholder="보험사 플랜명" />
            </td>
          </tr>
					<tr>
						<th>상품</th>
						<td>
							<select name="pr_name">
								<option value="">상품명 선택</option>
								<? while ($pr_row = $dbcon->fetch_array($RS_PR)) { ?>
									<option value="<?= $pr_row["subject"] ?>" <? if ($pr_name == $pr_row["subject"]) { ?>selected<? } ?>><?= $pr_row["subject"] ?></option>
								<? } ?>
							</select>

							<select name="ins_name">
								<option value="">보험사 선택</option>
								<? while ($ins_row = $dbcon->fetch_array($RS_Ins)) { ?>
									<option value="<?= $ins_row["subject"] ?>" <? if ($ins_name == $ins_row["subject"]) { ?>selected<? } ?>><?= $ins_row["subject"] ?></option>
								<? } ?>
							</select>

							<select name="plan_name">
								<option value="">플랜 선택</option>
								<? for ($c = 0; $c < count($Arr_plan_cd); $c++) { ?>
									<option value="<?= $Arr_plan_cd[$c + 1] ?>" <? if ($plan_name == $Arr_plan_cd[$c + 1]) { ?>selected<? } ?>><?= $Arr_plan_cd[$c + 1] ?></option>
								<? } ?>
							</select>

							<select name="chk_service">
								<option value="">서비스 선택</option>
								<option value="A" <? if ($chk_service == "A") { ?>selected<? } ?>>A</option>
								<option value="B" <? if ($chk_service == "B") { ?>selected<? } ?>>B</option>
								<option value="C" <? if ($chk_service == "C") { ?>selected<? } ?>>인슈플러스</option>
								<option value="D" <? if ($chk_service == "D") { ?>selected<? } ?>>플라잉닥터스</option>
								<option value="N" <? if ($chk_service == "N") { ?>selected<? } ?>>없음</option>
							</select>
						</td>

						<th>가입</th>
						<td>
							<input type="text" name="join_ch" class="w150" value="<?= $join_ch ?>" placeholder="가입채널명" />
							<select name="group_join_type">
								<option value="">단체 가입구분</option>
								<option value="B2B" <? if ($group_join_type == "B2B") { ?>selected<? } ?>>B2B</option>
								<option value="B2C" <? if ($group_join_type == "B2C") { ?>selected<? } ?>>B2C</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>기간</th>
						<td>
							<select name="search_date_txt">
								<option value="A.writedate" <? if ($search_date_txt == "A.writedate") { ?>selected<? } ?>>결제일</option>
								<option value="A.s_date" <? if ($search_date_txt == "A.s_date") { ?>selected<? } ?>>보험개시일</option>
								<option value="A.e_date" <? if ($search_date_txt == "A.e_date") { ?>selected<? } ?>>보험종료일</option>
								<option value="A.cancle_date" <? if ($search_date_txt == "A.cancle_date") { ?>selected<? } ?>>취소일</option>
							</select>
							<input type="text" name="search_date_s" class="calendar w100 ml10" value="<?= $search_date_s ?>" />
							~ <input type="text" name="search_date_e" class="calendar w100" value="<?= $search_date_e ?>" />

							<select name="order_step" class="ml10">
								<option value="">결제상태 선택</option>
								<option value="1" <? if ($order_step == "1") { ?>selected<? } ?>>입금전</option>
								<option value="2" <? if ($order_step == "2") { ?>selected<? } ?>>결제완료</option>
								<option value="N" <? if ($order_step == "N") { ?>selected<? } ?>>결제취소</option>
								<option value="P" <? if ($order_step == "P") { ?>selected<? } ?>>부분취소</option>
								<option value="R" <? if ($order_step == "R") { ?>selected<? } ?>>환불</option>
							</select>
						</td>

						<th>업체</th>
						<td>
							<input type="text" name="o_name_b2b" id="o_name_b2b" value="<?= $o_name_b2b ?>" style="background-color: #ededed;cursor:default;border: 1px solid #858585;width:150px;" readonly placeholder="한글명" />
							<input type="text" name="o_name_en_b2b" id="o_name_en_b2b" value="<?= $o_name_en_b2b ?>" style="background-color: #ededed;cursor:default;border: 1px solid #858585;width:150px;" readonly placeholder="영문명" />
							<a class="btn_normal" href="javascript:;" onclick="pop_client();">찾기</a>
						</td>
					</tr>
					<tr>
						<th>가입자</th>
						<td>
							<select name="search">
								<option value="o_name" <? if ($search == "o_name") { ?>selected<? } ?>>이름</option>
								<option value="o_phone" <? if ($search == "o_phone") { ?>selected<? } ?>>휴대폰번호</option>
								<option value="orderno" <? if ($search == "orderno") { ?>selected<? } ?>>주문번호</option>
							</select>
							<input type="text" name="search_text" class="w400" value="<?= $search_text ?>" />
							<input type="submit" value="검색" />
						</td>
					</tr>
				</table>
			</form>
			<!-- (e) 검색영역  -->

			<div class="btnWrap">
				<div class="leftWrap"><span class="totalCount">전체 : <strong><?= number_format($total_record) ?></strong>건</span></div>
				<div class="rightWrap">
					<a href="javascript:;" class="btn_excel" onclick="excel_go();">엑셀다운로드</a>
				</div>
			</div>

			<!--  (s) 리스트 영역  -->
			<form method="post" name="frmCheckDel" action="<?= $PHP_SELF ?>">

				<table class="adm-list-tb">
					<colgroup>
						<col width="5%" />
						<col width="*" />
						<col width="8%" />
						<col width="10%" />
						<col width="8%" />
						<col width="8%" />
						<col width="5%" />
						<col width="5%" />
						<col width="5%" />
						<col width="8%" />
						<col width="5%" />
						<col width="5%" />
						<col width="5%" />
						<col width="8%" />
						<col width="8%" />
					</colgroup>
					<tr>
						<th>NO</th>
						<th>상품명</th>
						<th>보험사</th>
						<th>플랜명</th>
						<th>개시일</th>
						<th>종료일</th>
						<th>보험기간</th>
						<th>이름</th>
						<th>가입자수</th>
						<th>가입채널</th>
						<th>결제상태</th>
						<th>상품가</th>
						<th>결제금액</th>
						<th>가입일</th>
						<th>취소일</th>
					</tr>
					<? if ($total_record == 0) { ?>
						<tr onClick="view_go()" class="click">
							<td colspan="16"><?= $GLOBALS["msg_list_notdata"] ?></td>
						</tr>
						<?
					} else {
						while ($rows = $dbcon->fetch_array($result)) {
							extract($rows);
							unset($rows);
							$bg = "";
							if ($order_step == "N" || $order_step == "P") { //결제취소, 부분취소
								$bg = "#ededed";
							} else if ($order_step == "R") { //환불
								$bg = "#eb8b8b";
							}
						?>
							<tr onClick="view_go('<?= $orderno ?>')" class="click" style="background:<?= $bg ?>;">
								<td><?= $no ?></td>
								<td><?= $pr_name ?></td>
								<td><?= print_ins($guarantee1_ins_seq) ?></td>
								<td><?= $plan_name ?></td>
								<td><?= $s_date ?> <?= $s_date_time ?>시</td>
								<td><?= $e_date ?> <?= $e_date_time ?>시</td>
								<td><?= $ins_period ?> <?= $arr_chk_p_gubun[$chk_p] ?></td>
								<td><?= maskingKoName(all_seed_dec($o_name)) ?></td>
								<td><?= $join_cnt ?>명</td>
								<td><?= $partnership_name ?></td>
								<td><?= $arr_ord_step[$order_step] ?></td>
								<td class="r"><?= number_format($ins_amount + $service_amount) ?></td>
								<td class="r"><?= number_format($t_amount) ?></td>
								<td><?= substr($writedate, 0, 10) ?></td>
								<td>
								<? if($cancle_date != "0000-00-00 00:00:00"){
									echo substr($cancle_date, 0, 10);
								} ?>
								</td>
							</tr>
					<?
							$no = $no - 1;
						}
					}
					unset($result);

					?>
				</table>
				<input type=hidden name=f_delete>
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




<? include_once $path_admin . "inc/reason_popup.php"; ?>

<? include $path_admin . "inc/footer.php"; ?>
<? $dbcon->dbcon_close(); ?>