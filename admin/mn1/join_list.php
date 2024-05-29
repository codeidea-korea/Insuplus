<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out); // 관리자 체크

$tm = "MN1";
$lm = "";
include $path_admin . "inc/header.php";
?>
<?
$page_btn_prev = ">>"; // > 버튼
$page_btn_next = "<<"; // < 버튼

// 페이지 설정
$page          = REQSTR($page, 1);
$num_per_page      = REQSTR($num_per_page, 30);
$page_per_block      = REQSTR($page_per_block, 10);
$first          = $num_per_page * ($page - 1);
$last          = $num_per_page * $page;

// 검색설정
$query_where      = "";

#### 검색 설정 Start
$pr_name        = REQSTR($pr_name, "");
$ins_name        = REQSTR($ins_name, "");
$plan_name        = REQSTR($plan_name, "");
$chk_service      = REQSTR($chk_service, "");
$join_status        = REQSTR($_GET["join_status"], "");
$join_ch        = REQSTR($join_ch, "");
$group_join_type    = REQSTR($group_join_type, "");
$client_id        = REQSTR($client_id, "");
$o_name_b2b        = REQSTR($o_name_b2b, "");
$o_name_en_b2b      = REQSTR($o_name_en_b2b, "");

$search          = REQSTR($search, "");
$search_text      = REQSTR($search_text, "");

$search_orderby      = REQSTR($search_orderby, "");
$search_sort      = REQSTR($search_sort, "");

$search_date_txt    = REQSTR($search_date_txt, "");
$search_date_s      = REQSTR($search_date_s, "");
$search_date_e      = REQSTR($search_date_e, "");
$search_ins_date_order = "";

// 2023-11-08 added
$category_cd      = REQSTR($category_cd  , "");
$ins_plan_name    = REQSTR($ins_plan_name, "");

if (strlen($pr_name) > 0) $query_where .= " and A.pr_name = '" . $pr_name . "' ";
if (strlen($ins_name) > 0) $query_where .= " and A.ins_name = '" . $ins_name . "' ";
if (strlen($plan_name) > 0) $query_where .= " and A.plan_name = '" . $plan_name . "' ";
if (strlen($chk_service) > 0) $query_where .= " and A.chk_service = '" . $chk_service . "' ";
if (strlen($join_status) > 0) $query_where .= " and B.join_status = '" . $join_status . "' ";

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
} else if($group_join_type === "B2B") {
  $query_where .= "and B.group_join_type = '" . $group_join_type . "'";
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

$parameter = "&pr_name=" . $pr_name . "&ins_name=" . $ins_name . "&plan_name=" . $plan_name . "&chk_service=" . $chk_service .
  "&search=" . $search . "&search_text=" . $search_text . "&search_orderby=" . $search_orderby .
  "&search_sort=" . $search_sort . "&num_per_page=" . $num_per_page . "&join_status=" . $join_status .
  "&search_date_txt=" . $search_date_txt . "&search_date_s=" . $search_date_s . "&search_date_e=" . $search_date_e .
  "&join_ch=" . $join_ch . "&group_join_type=" . $group_join_type . "&client_id=" . $client_id . "&o_name_b2b=" . $o_name_b2b . 
  "&o_name_en_b2b=" . $o_name_en_b2b . "&category_depth0=" . $category_depth0 . "&category_depth1=" . $category_depth1 . 
  "&category_depth2=" . $category_depth2 . "&category_depth3=" . $category_depth3 . "&ins_plan_name=" . $ins_plan_name . "&category_cd=" . $category_cd;

#### 검색 설정 End

// 쿼리설정
$field         = " A.*, B.*, C.partnership_name as partnership_name, D.guarantee1_ins_seq";

$table      = " tbl_order_list A inner join tbl_order_list_join B on A.orderno=B.orderno left join tbl_board_partner C ON A.join_ch = C.seq left join tbl_board_plan D on A.plan_cd = D.seq ";
$where      = $query_where;
$orderby      = $search_orderby;
$limit        = $first . ", " . $num_per_page;

$ArrRS      = $dbcon->getList($field, $table, $where, $orderby, $limit);
$total_record  = $ArrRS[0];
$result      = $ArrRS[1];
unset($ArrRS);

// 페이지 & 리스트 설정
$total_page    = ceil($total_record / $num_per_page);
$no        = $total_record - $first;

// 상품 코드 불러오기
$SQL_PR = "select seq,subject from tbl_board_product  ";
$RS_PR = $dbcon->query($SQL_PR);
// 보험사 불러오기
$SQL_Ins = "select seq,subject from tbl_board_ins_list  ";
$RS_Ins = $dbcon->query($SQL_Ins);

//채널 목록

?>
<script type="text/javascript">
$(document).ready(function() {
var clareCalendar = {<?= $calendar_opt ?>});
</script>
<script>
  function view_go(n) {
    location.href = "join_view.php?seq=" + n + "<?= $parameter ?>";
  }

  function excel_go(name) {
    if (name == 'all') {
      location.href = 'excel_join.php?mode=excel&<?= $GLOBALS["parameter"] ?>';
    } else if (name == 'hanwha') {
      location.href = 'excel_hanwha.php?mode=excel&<?= $GLOBALS["parameter"] ?>';
    } else if (name == 'hyundai') {
      location.href = 'excel_hyundai.php?mode=excel&<?= $GLOBALS["parameter"] ?>';
    } else if (name == 'meritz') {
      location.href = 'excel_meritz.php?mode=excel&<?= $GLOBALS["parameter"] ?>';
    }
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
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?= $url_admin ?>images/admin_st_ball.gif"></td>
    <td valign="top" class="a_st">가입자</td>
  </tr>
  <tr>
    <td colspan="2" height="20"></td>
  </tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td>
      <!-- (s) 검색영역  -->
      <form name="frm_group_join" method="get" action="<?= $PHP_SELF ?>" onsubmit="return search_go()">
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
                <option value="A" <? if ($chk_service == "A") { ?>selected<? } ?>>A타입</option>
                <option value="B" <? if ($chk_service == "B") { ?>selected<? } ?>>B타입</option>
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
                <option value="B.cancle_date" <? if ($search_date_txt == "B.cancle_date") { ?>selected<? } ?>>취소일</option>
              </select>
              <input type="text" name="search_date_s" class="calendar w100 ml10" value="<?= $search_date_s ?>" />
              ~ <input type="text" name="search_date_e" class="calendar w100" value="<?= $search_date_e ?>" />

              <select name="join_status" class="ml10">
                <option value="">가입상태 선택</option>
                <option value="Y" <? if ($join_status == "Y") { ?>selected<? } ?>>가입완료</option>
                <option value="N" <? if ($join_status == "N") { ?>selected<? } ?>>가입취소</option>
                <option value="R" <? if ($join_status == "R") { ?>selected<? } ?>>중도해지</option>
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
              <input type="text" name="search_text" class="w400" value="<?= $search_text ?>" style="margin-left: 10px;" />
              <input type="submit" value="검색" />
            </td>
            <th>주민번호체크</th>
            <td>
              <input type="text" jumin placeholder="880105-1234567"/>
              <button type="button" jumin>check</button>
              <script>
                function checkPrivateNumber(pNum){
                  const forien = [5, 6, 7, 8]
                  const arr = [2,3,4,5,6,7,8,9,2,3,4,5];
                  const num = pNum.replace(/-/g, '');
                  const magicNumber = 11;

                  if (!num || num.length !== 13) return false;
                  else if (forien.includes(Number(num[6]))) return true;

                  const fields = arr.map((n, i) => n * num[i]);
                  const sum = fields.reduce((p, n) => p + n, 0);

                  return Number(num[12]) === ((magicNumber - (sum % magicNumber)) % 10 );
                }
                document.querySelector('button[jumin]').addEventListener('click', (e)=>{
                  const pNum = document.querySelector('input[jumin]').value;
                  const bool = checkPrivateNumber(pNum);
                  if (bool) alert(' 정상 ');
                  else alert(' 오류 ');
                })
              </script>
            </td>
          </tr>
        </table>
      </form>
      <!-- (e) 검색영역  -->

      <div class="btnWrap">
        <div class="leftWrap"><span class="totalCount">전체 : <strong><?= number_format($total_record) ?></strong>건</span></div>
        <div class="rightWrap">
          <a href="javascript:;" class="btn_excel" onClick="excel_go('meritz')">메리츠보험 다운로드</a>
          <a href="javascript:;" class="btn_excel" onClick="excel_go('hanwha')">한화손해보험 다운로드</a>
          <a href="javascript:;" class="btn_excel" onClick="excel_go('hyundai')">현대해상 다운로드</a>
          <a href="javascript:;" class="btn_excel" onClick="excel_go('all')">엑셀다운로드</a>

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
            <col width="10%" />
            <col width="10%" />
            <col width="5%" />
            <col width="5%" />
            <col width="8%" />
            <col width="8%" />
            <col width="8%" />
            <col width="5%" />
            <col width="8%" />
          </colgroup>
          <tr>
            <th>NO</th>
            <th>상품명</th>
            <th>보험사</th>
            <th>플랜명</th>
            <th>개시일</th>
            <th>종료일</th>
            <th class="btn">보험기간
              <a href="#" class="up">▲</a>
              <a href="#" class="down">▼</a>
            </th>
            <th class="btn">이름
              <a href="#" class="up">▲</a>
              <a href="#" class="down">▼</a>
            </th>
            <th>연락처</th>
            <th>가입채널</th>
            <th>가입상태</th>
            <th class="btn">상품가
              <a href="#" class="up">▲</a>
              <a href="#" class="down">▼</a>
            </th>
            <th class="btn">가입일
              <a href="#" class="up">▲</a>
              <a href="#" class="down">▼</a>
            </th>
          </tr>
          <? if ($total_record == 0) { ?>
            <tr>
              <td colspan="14">등록된 데이터가 없습니다.</td>
            </tr>
            <?
          } else {
            while ($rows = $dbcon->fetch_array($result)) {
              extract($rows);
              unset($rows);
              if ($chk_p == "Y") {
                $s_date = $s_date . " " . $s_date_time . "시";
                $e_date = $e_date . " " . $e_date_time . "시";
              } else if ($chk_p == "N") {
                $s_date = $s_date;
                $e_date = $e_date;
              }
              $bg = "";
              if ($join_status == "N") { //가입취소
                $bg = "#ededed";
              } else if ($join_status == "R") { //중도해지
                $bg = "#eb8b8b";
              }
              //if ($join_status=="S"){$bg = "#f3c7d5";}
            ?>
              <tr onClick="view_go('<?= $seq ?>')" class="click" style="background:<?= $bg ?>;">
                <td><?= $no ?></td>
                <td><?= $pr_name ?></td>
                <td><?= print_ins($guarantee1_ins_seq) ?></td>
                <td><?= $plan_name ?></td>
                <td><?= $s_date ?></td>
                <td><?= $e_date ?></td>
                <td><?= $ins_period ?> <?= $arr_chk_p_gubun[$chk_p] ?></td>
                <td><?= all_seed_dec($o_name) ?></td>
                <td><?= all_seed_dec($o_phone) ?></td>
                <td><?= $partnership_name ?></td>
                <td><?= $arr_join_step[$join_status] ?></td>
                <td class="r"><?= number_format($join_amount + $join_service) ?>원</td>
                <td><?= substr($regdate, 0, 10) ?></td>
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





<? include $path_admin . "inc/footer.php"; ?>
<? $dbcon->dbcon_close(); ?>