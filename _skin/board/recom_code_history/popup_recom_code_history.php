<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);
?>
<?
	$tm = "board";
	$lm = "";
	include $path_admin."inc/header_pop.php";
	
?>
<?


	if ( $_GET["bc_id"] ) {
		$bc_id = $_GET["bc_id"];
	}
	else {
		$bc_id = $_POST["bc_id"];
	}

	if ( !$bc_id ) {
		$SQL = "
			select
				bc_id, bc_name
			from
				config_board_list
			order by
				bc_name
			limit 0, 1
		";
	}
	else {
		$SQL = "
			select
				bc_id, bc_name
			from
				config_board_list
			where
				bc_id = '".$bc_id."'
		";
	}

//	echo $SQL."<BR>";
	$TempRow = $dbcon->fetch_array($dbcon -> query($SQL));

	$bc_id = $TempRow["bc_id"];
	$bc_name = $TempRow["bc_name"];

//	echo "bc_id : " .$bc_id."<BR>";
//	echo "bc_name : " .$bc_name."<BR>";

	unset($TempRow);

	$html = new html;
	//echo $html -> getAdminTitle($bc_name);

	//exit;
?>
<div class="popupWrap">
	<header>
		<h1>추천코드 사용내역</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	
	<div class="popContWrap">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" >
			<tr>
				<td>
					<!-- (s) 검색영역  -->
					<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
					<table class="adm-searchForm">
						<colgroup>
							<col width="8%" />
							<col width="20%" />
							<col width="92%" />
						</colgroup>
						<tr>
							<th>이름</th>
							<td colspan="2">
								<input type="hidden" name="search" value="contractor" />
								<input type="text" name="search_text" value="<?=$search_text?>" />
								<input type="submit" value="검색" />
							</td>
						</tr>
					</table>			
					</form>
					<!-- (e) 검색영역  -->
					
					<table class="adm-list-tb">
					<colgroup>
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
					</colgroup>
					<tr>
						<th>상품명</th>
						<th>제휴사</th>
						<th>추천코드</th>
						<th>계약자</th>
						<th>상품가격</th>
						<th>등록일</th>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>
</html>
<? $dbcon -> dbcon_close();?>
