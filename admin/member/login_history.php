<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out); // 관리자 체크

$tm = "member";
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
$login_id        = REQSTR($login_id, "");
$ip        = REQSTR($ip, "");
$act        = REQSTR($act, "");
$regist_ip        = REQSTR($regist_ip, "");

$search          = REQSTR($search, "");
$search_text      = REQSTR($search_text, "");

$search_orderby      = REQSTR($search_orderby, "");

$search_date_txt    = REQSTR($search_date_txt, "");
$search_date_s      = REQSTR($search_date_s, "");
$search_date_e      = REQSTR($search_date_e, "");

if (strlen($search_text) > 0) {
  if($search == "login_id") {
    $query_where .= " and ".$search." like '%" . $search_text . "%'  ";
  } else if($search == "ip") {
    $query_where .= " and ".$search." like '%" . $search_text . "%'  ";
  } else if($search == "regist_ip") {
    $query_where .= " and ".$search." like '%" . $search_text . "%'  or ip like '%" . $search_text . "%' ";
  } else {
    $query_where .= " and ".$search." = '" . $search_text . "'  ";
  }
}

if (strlen($search_date_s) > 0) {
  $query_where .= " and " . $search_date_txt . " >= '" . $search_date_s . " 00:00:00' ";
}
if (strlen($search_date_e) > 0) {
  $query_where .= " and " . $search_date_txt . " <= '" . $search_date_e . " 23:59:59' ";
}

$search_orderby = " his_seq DESC";

$parameter = "&login_id=".$login_id."&ip=".$ip."&act=".$act."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_date_txt=".$search_date_txt."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;

#### 검색 설정 End

// 쿼리설정
$field         = " *";

$table      = " tbl_login_his ";
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
?>
<script type="text/javascript">
$(document).ready(function() {
var clareCalendar = {<?= $calendar_opt ?>});
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?= $url_admin ?>images/admin_st_ball.gif"></td>
    <td valign="top" class="a_st">로그인 기록</td>
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
            <col width="10%" />
            <col width="90%" />
          </colgroup>
          <tr>
            <th>기간</th>
            <td>
              <select name="search_date_txt">
                <option value="reg_dt" <? if ($search_date_txt == "reg_dt") { ?>selected<? } ?>>등록일</option>
              </select>
              <input type="text" name="search_date_s" class="calendar w100 ml10" value="<?= $search_date_s ?>" />
              ~ <input type="text" name="search_date_e" class="calendar w100" value="<?= $search_date_e ?>" />
            </td>
          </tr>
          <tr>
            <th>가입자</th>
            <td>
              <select name="search">
                <option value="login_id" <? if ($search == "login_id") { ?>selected<? } ?>>ID</option>
                <!-- option value="ip" <? if ($search == "ip") { ?>selected<? } ?>>IP</option -->
                <option value="regist_ip" <? if ($search == "regist_ip") { ?>selected<? } ?>>IP</option>
                <option value="act" <? if ($search == "act") { ?>selected<? } ?>>사용자액션</option>
              </select>
              <input type="text" name="search_text" class="w400" value="<?= $search_text ?>" style="margin-left: 10px;" />
              <input type="submit" value="검색" />
            </td>
          </tr>
        </table>
      </form>
      <!-- (e) 검색영역  -->

      <div class="btnWrap">
        <div class="leftWrap"><span class="totalCount">전체 : <strong><?= number_format($total_record) ?></strong>건</span></div>
        <div class="rightWrap">
          
        </div>
      </div>

      <!--  (s) 리스트 영역  -->
      <form method="post" name="frmCheckDel" action="<?= $PHP_SELF ?>">

        <table class="adm-list-tb">
          <colgroup>
            <col width="10%" />
            <col width="20%" />
            <col width="20%" />
            <col width="20%" />
            <col width="30%" />
          </colgroup>
          <tr>
            <th>NO</th>
            <th>로그인아이디</th>
            <th>IP</th>
            <th>사용자액션</th>
            <th>등록일시</th>
          </tr>
          <? if ($total_record == 0) { ?>
            <tr>
              <td colspan="5">등록된 데이터가 없습니다.</td>
            </tr>
            <?
          } else {
            while ($rows = $dbcon->fetch_array($result)) {
              extract($rows);
              unset($rows);
              $bg = "";
              if ($act == "logout") { //가입취소
                $bg = "#ededed";
              }
            ?>
              <tr style="background:<?= $bg ?>;">
                <td><?= $no ?></td>
                <td><?= $login_id ?></td>
                <!--td><?= $ip ?></td-->
                <td><?= $regist_ip ? $regist_ip : $ip ?></td>
                <td><?= $act ?></td>
                <td><?= substr($reg_dt, 0, 20) ?></td>
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