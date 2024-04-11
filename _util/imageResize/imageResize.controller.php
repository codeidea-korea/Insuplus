<?php
/**
 * @class  imageResizeController
 * @author dexter (flavorkey@gmail.com)
 * @brief  imageResize 모듈의 controller 클래스
 **/

class imageResizeController extends imageResize {

	/**
	 * @brief 초기화
	 **/
	function init() {
	}

	function triggerInsertFile(&$args) {
		$oModuleModel = &getModel('module');
		$config = $oModuleModel->getModuleConfig('imageResize');

		if($config->imageResize_use!='Y') return new Object();

		$oModuleInfo=$oModuleModel->getModuleInfoByModuleSrl($args->module_srl);
		$file_mid= $oModuleInfo->mid;

		if( $config->target_mid != '' )
			if(strpos($config->target_mid,$file_mid)===false) return new Object();



		$file=$args->uploaded_filename;
		$file_names=explode(".",$file);
		$num=count($file_names);

		if($num>0) {
			$ext=strtolower($file_names[$num-1]);

			if($ext=='jpg' || $ext=='gif' || $ext=='png' || $ext=='jpeg') {
				if($ext=='jpg') $ext='jpeg';
				$ref_width=$config->imageResize_width;
				list($image_width, $image_height)=getimagesize($file);

				if($image_width>$ref_width) {

					$realfile=realpath($file);

/* big or not check memory_limit
$imageInfo = GetImageSize($imageFilename);
$memoryNeeded = Round(($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + Pow(2, 16)) * 1.65);
*/

					$new_height=round($image_height*$ref_width/$image_width);

					$image_p = imagecreatetruecolor($ref_width, $new_height);

					if($ext=='jpeg')
						$image=imagecreatefromjpeg($realfile);
					else if($ext=='gif')
						$image=imagecreatefromgif($realfile);
					else if($ext=='png')
						$image=imagecreatefrompng($realfile);
					if(!$image) return new Object();

					$res=imagecopyresampled($image_p,$image,0,0,0,0,$ref_width,$new_height,$image_width,$image_height);
					if(!$res) return new Object();

					if($ext=='jpeg')
						imagejpeg($image_p,$realfile);
					else if($ext=='gif')
						imagegif($image_p,$realfile);
					else if($ext=='png')
						imagepng($image_p,$realfile);
					 
					$qry->file_srl=$args->file_srl;
					$qry->file_size=filesize($realfile);
					executeQuery('imageResize.updateFileSize', $qry);
				}

			}
		}

		return new Object();
	}
}
?>
