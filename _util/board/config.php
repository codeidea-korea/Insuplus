<?
	// new 이미지
	$img_new = "<img src='".$url_skin_board.$bc_skin."/images/new.gif' align='absmiddle' border='0'>";
	$img_blank = "<img src='".$url_skin_board.$bc_skin."/images/icon_re.gif' align='absmiddle' border='0'>";

	$upload_size = 1024 * 1024 * 2;// "2048000"

	$image_view_width = 600;

	$UpFileDirectory = $path_root."_data/bbs/".$bc_id."/";
	//echo $UpFileDirectory."<BR>";


	$list_type = "gallery";

	$Stop_Extension		= array('jpg', 'gif', 'bmp', 'jpeg');
	$Stop_Size				= 1024 * 1024 * 20;
	$UpFileDirectory		= $UpFileDirectory;
	$UpFileCategory		= $bc_id;
?>