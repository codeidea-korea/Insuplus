<?  if($joinType=="2") { ?>
<ul>
	<li <?=basename($_SERVER['REQUEST_URI'])=="register_step_00.php" ? "class='active'":"";?> data-title='1'>플랜비교</li>
	<li <?=basename($_SERVER['REQUEST_URI'])=="register_step_01.php" ? "class='active'":"";?> data-title='2'>플랜선택</li>
	<li <?=basename($_SERVER['REQUEST_URI'])=="register_step_02.php" ? "class='active'":"";?> data-title='3'>계약자정보</li>
	<li <?=basename($_SERVER['REQUEST_URI'])=="register_step_03.php" ? "class='active'":"";?> data-title='4'>약관동의</li>
	<li <?=basename($_SERVER['REQUEST_URI'])=="register_step_04.php" ? "class='active'":"";?> data-title='5'>결제하기</li>
</ul>		
<? } else { ?>
<ul>
	<li <?=basename($_SERVER['REQUEST_URI'])=="register_step_01.php" ? "class='active'":"";?> data-title='1'>플랜선택</li>
	<li <?=basename($_SERVER['REQUEST_URI'])=="register_step_02.php" ? "class='active'":"";?> data-title='2'>계약자정보 입력</li>
	<li <?=basename($_SERVER['REQUEST_URI'])=="register_step_03.php" ? "class='active'":"";?> data-title='3'>약관동의</li>
	<li <?=basename($_SERVER['REQUEST_URI'])=="register_step_04.php" ? "class='active'":"";?> data-title='4'>결제하기</li>
</ul>			
<? } ?>