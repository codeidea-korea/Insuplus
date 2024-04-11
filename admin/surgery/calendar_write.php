<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

if ($ss_u_level<6){
    echo "<script>alert('팀장회원 이상만 사용이 가능합니다.');history.back();</script>";
    exit;
};
?>
<?
$tm = "intra";
$lm = "";
include $path_admin."inc/header.php";
?>


<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">수술 스케쥴</td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<table width="698" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<?
			include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
			admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

			if ($idx){
			    include_once $_SERVER["DOCUMENT_ROOT"]."/admin/surgery/config.php";

			    $sSQL = "SELECT * FROM tbl_doctor WHERE idx=$idx";
			    $result = mysql_query( $sSQL, $con) or die("DB Error : $sSQL");
			    $row = mysql_fetch_array( $result);
			    $from		= $row["r_from"];
			    $to		= $row["r_to"];
			    $name1	= $row["name1"];
			    $name2	= $row["name2"];
			    $name3	= $row["name3"];
			    $doctor	= $row["doctors"];
			    $year		= $row["r_year"];
			    $month	= $row["r_month"];
			    $day		= $row["r_day"];
			    $t_area	= $row["t_area"];
			    $mode	= "modify";
			}
			else{
			    $mode	= "write";
			}
			?>
			<link href="/_css/admin.css" rel="stylesheet" type="text/css">
			<link href="/share/css/content.css" rel="stylesheet" type="text/css">
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<table width="350" border="0" cellspacing="1" cellpadding="0" class="mgt10" align="left" bgcolor="cccccc">
				<form name="frm" method="post">
				<input type="hidden" name="idx" value="<?=$idx?>">
				<input type="hidden" name="year" value="<?=$year?>">
				<input type="hidden" name="month" value="<?=$month?>">
				<input type="hidden" name="day" value="<?=$day?>">
				<input type="hidden" name="mode" value="<?=$mode?>">
				<input type="hidden" name="st_in" value="">
				<input type="hidden" name="ed_in" value="">
				<input type="hidden" name="t_in" value="">
				<?
				//$name1=iconv("euc-kr", "utf-8", $_GET[name1]);
				//$name2=iconv("euc-kr", "utf-8", $_GET[name2]);
				//$name3=iconv("euc-kr", "utf-8", $_GET[name3]);

				if($name1==""){
				    $name1="";
				}
				if($name2==""){
				    $name2="";
				}
				if($name3==""){
				    $name3="";
				}
				?>
				<tr bgcolor="#FFFFFF">
					<td  class="pdlr25 bg_gry" valign="top"><?=$year?>년 <?=$month?>월 <?=$day?>일 스케쥴을 입력해 주세요</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td  class="pdlr25 bg_gry" valign="top">
						<table>
							<tr>
								<td><div class="mgt10">선택의사</td>
								<td>
									<div class="mgt10">
										<select name="doctor">
										<option value="김경길" <?if($doctor=="김경길"){echo "selected";}?>>김경길 원장님</option>
										<option value="박준성" <?if($doctor=="박준성"){echo "selected";}?>>박준성 원장님</option>
										<option value="정도현" <?if($doctor=="정도현"){echo "selected";}?>>정도현 원장님</option>
										<option value="최정기" <?if($doctor=="최정기"){echo "selected";}?>>최정기 원장님</option>
										<option value="이호원" <?if($doctor=="이호원"){echo "selected";}?>>이호원 원장님</option>
										<option value="신형호" <?if($doctor=="신형호"){echo "selected";}?>>신형호 원장님</option>
										<option value="조윤정" <?if($doctor=="조윤정"){echo "selected";}?>>조윤정 원장님</option>
										<option value="서정훈" <?if($doctor=="서정훈"){echo "selected";}?>>서정훈 원장님</option>
										<option value="염동주" <?if($doctor=="염동주"){echo "selected";}?>>염동주 원장님</option>
										</select>
									</div>
								</tr>
								<tr>
									<td><div class="mgt10">수술</td>
									<td>
										<div class="mgt10">
											<select name="name1">
											<option value="백내장" <?if($name1=="백내장"){echo "selected";}?>>백내장</option>
											<option value="MEL80라섹" <?if($name1=="MEL80라섹"){echo "selected";}?>>MEL80라섹</option>
											<option value="EX500라섹" <?if($name1=="EX500라섹"){echo "selected";}?>>EX500라섹</option>
											<option value="ICL" <?if($name1=="ICL"){echo "selected";}?>>ICL</option>
											<option value="안검성형술" <?if($name1=="안검성형술"){echo "selected";}?>>안검성형술</option>
											<option value="호츠수술" <?if($name1=="호츠수술"){echo "selected";}?>>호츠수술</option>
											<option value="익상편" <?if($name1=="익상편"){echo "selected";}?>>익상편</option>
											<option value="AVASTIN" <?if($name1=="AVASTIN"){echo "selected";}?>>AVASTIN</option>
											</select>
										</div>
									</tr>
									<tr>
										<td><div class="mgt10">지점</td>
										<td>
											<div class="mgt10">
												<select name="t_area">
													<!-- <option value="">선택하세요</option> -->
													<option value="1" <?if ($t_area=="1"){echo "selected";} ?>>천안역점</option>
													<option value="2" <?if ($t_area=="2"){echo "selected";} ?>>쌍용역점</option>
													<option value="3" <?if ($t_area=="3"){echo "selected";} ?>>온양역점</option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td><div class="mgt10">대상</td>
										<td>
											<div class="mgt10">
												<input type="text" name="name2" value="<?=$name2?>" style="width:250px;">
											</div>
										</td>
									</tr>
									<tr>
										<td><div class="mgt10">시간</td>
										<td>
											<div class="mgt10">
												<select name="from" onchange="chk()">
												<?$kk
												= 0;
												?>
												<?for($k=9;$k<20;$k++){?>
												<option value="<?=$k
												?>:00" <?if($from=="$k:00"){echo"selected";}?>><?=$k?>:00</option>
												<option value="<?=$k
												?>:15" <?if($from=="$k:15"){echo"selected";}?>><?=$k?>:15</option>
												<option value="<?=$k
												?>:30" <?if($from=="$k:30"){echo"selected";}?>><?=$k?>:30</option>
												<option value="<?=$k
												?>:45" <?if($from=="$k:45"){echo"selected";}?>><?=$k?>:45</option>
												<?
												$kk++;
												}
												?>
												<option value="20:00" <?if($from=="20:00"){echo "selected";}?>>20:00</option>
												</select>
												~
												<select name="to" onchange="chk()">
												<?for($k=9;$k<20;$k++){?>
												<option value="<?=$k
												?>:00" <?if($to=="$k:00"){echo"selected";}?>><?=$k?>:00</option>
												<option value="<?=$k
												?>:15" <?if($to=="$k:15"){echo"selected";}?>><?=$k?>:15</option>
												<option value="<?=$k
												?>:30" <?if($to=="$k:30"){echo"selected";}?>><?=$k?>:30</option>
												<option value="<?=$k
												?>:45" <?if($to=="$k:45"){echo"selected";}?>><?=$k?>:45</option>
												<?}
												?>
												<option value="20:00" <?if($from=="20:00"){echo "selected";}?>>20:00</option>
												</select>
											</div>
										</tr>
									</table>
								</td>
							</tr>
							<tr bgcolor="#FFFFFF">
								<td align="center" height="50">

									<input type="button" value="저장" onclick="chk_submit()">

									<?if($idx){?>
												<input type="button" value="삭제" onclick="OnDelete()">
												<?}
									?>
								</td>
							</tr>
							</form>
						</table>


					</td>
				</tr>
			</table>


			<?
												include $path_admin."inc/footer.php";
			?>


			<?
												$dbcon -> dbcon_close();
			?>
			<SCRIPT LANGUAGE="JavaScript">
			<!--
			function chk(){
			    document.frm.st_in.value = document.all.from.selectedIndex;
			    document.frm.ed_in.value = document.all.to.selectedIndex;
			    document.frm.t_in.value = document.all.to.selectedIndex - document.all.from.selectedIndex;
			}

			function chk_submit(){

			    if(document.frm.name2.value==""){
			        alert('대상명을 입력해주시기 바랍니다.');
			        document.frm.name2.focus();
			        return;
			    }

			    document.frm.st_in.value = document.all.from.selectedIndex;
			    document.frm.ed_in.value = document.all.to.selectedIndex;
			    document.frm.t_in.value = document.all.to.selectedIndex - document.all.from.selectedIndex;

			    if (document.all.to.selectedIndex - document.all.from.selectedIndex < 0)
			    {
			        alert('시간선택이 잘못되었습니다.');
			        return;
			    }

			    document.frm.action="calendar_write_ok.php";
			    document.frm.submit();
			}

			function OnDelete(){
			    if(confirm('삭제하시겠습니까?\n삭제후 복원하실 수 없습니다.')){
			        document.frm.action="calendar_delete.php";
			        document.frm.submit();
			    }
			}
			//-->
			</SCRIPT>
