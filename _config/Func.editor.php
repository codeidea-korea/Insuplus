<?php
function getEditorHead() {
}


// FCKEditor Insert Header
// 전송후 stripslashes 해줘야 한다.
// $sValue = stripslashes( $_POST['FCKeditor1'] ) ;


function getEditor($Obj, $val, $width="100%", $height="300", $skin="silver", $toolbar="bluecarpet") {
	global $path_fckeditor, $url_fckeditor;

	include_once $path_fckeditor."fckeditor.php";
	$sBasePath = $url_fckeditor;

	$oFCKeditor = new FCKeditor($Obj) ;							// 폼명
	$oFCKeditor->Width		= $width ;
	$oFCKeditor->Height		= $height ;
	$oFCKeditor->BasePath = $sBasePath ;									// 설치경로
	$oFCKeditor->Config['AutoDetectLanguage'] = true ;				// 자동언어선택 (true, false)
	$oFCKeditor->Config['DefaultLanguage']		= 'ko' ;					// 언어 선택 ('en' , 'ko' )
	$oFCKeditor->ToolbarSet = $toolbar;								// 툴바 선택 ('default', 'basic', 'bluecarpet')
	$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/'.$skin.'/' ;				// 스킨선택 ( 'default' , 'office2003', 'silver' )

	$oFCKeditor->Value = $val ;				// 기본 value
	$oFCKeditor->Create() ;
}

function getEditorFoot() {

}

?>
<?php
	/*
	#### USE
	$oFCKeditor = new FCKeditor('FCKeditor1') ;							// 폼명
	$oFCKeditor->BasePath = $sBasePath ;									// 설치경로
	$oFCKeditor->Config['AutoDetectLanguage'] = true ;				// 자동언어선택 (true, false)
	$oFCKeditor->Config['DefaultLanguage']		= 'ko' ;					// 언어 선택 ('en' , 'ko' )
	$oFCKeditor->ToolbarSet = "bluecarpet";								// 툴바 선택 ('default', 'basic', 'bluecarpet')
	$oFCKeditor->Config['SkinPath'] = $sBasePath . 'editor/skins/office2003/' ;				// 스킨선택 ( 'default' , 'office2003', 'silver' )
	$oFCKeditor->Value = '' ;				// 기본 value
	$oFCKeditor->Create() ;

	#### Request
	?>
	<table border="1" cellspacing="0" id="outputSample">
		<colgroup><col width="80"><col></colgroup>
		<thead>
			<tr>
				<th>Field Name</th>
				<th>Value</th>
			</tr>
		</thead>

	<?php

	if ( isset( $_POST ) )
		$postArray = &$_POST ;			// 4.1.0 or later, use $_POST
	else
		$postArray = &$HTTP_POST_VARS ;	// prior to 4.1.0, use HTTP_POST_VARS

	foreach ( $postArray as $sForm => $value )
	{
		if ( get_magic_quotes_gpc() )
			$postedValue = htmlspecialchars( stripslashes( $value ) ) ;
		else
			$postedValue = htmlspecialchars( $value ) ;

	?>
		<tr>
			<th><?php echo $sForm?></th>
			<td><pre><?php echo $postedValue?></pre></td>
			<td><pre><?php echo $value?></pre></td>
		</tr>
	<?php
	}
	?>
	</table>
	*/
?>