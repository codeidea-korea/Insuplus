<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>
						<h4 class="titleTy01 mT50">전체 의견 <em>(<?=$CmtTotalCnt?>)</em></h4>
						<ul class="commentAll mT30">

<?}else{
//관리자 모드입니다.
?>
<table border=0 cellspacing=0 cellpadding=0 align=center width=100%>
<?}?>