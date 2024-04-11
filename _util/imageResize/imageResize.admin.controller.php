<?php
/**
 * @class  imageResizeAdminController
 * @author dexter (flavorkey@gmail.com)
 * @brief imageResize 모듈의 admin controller 클래스
 **/

class imageResizeAdminController extends imageResize {

	/**
	 * @brief 초기화
	 **/
	function init() {
	}

	function procImageResizeAdminSetup() {
		$oModuleController = &getController('module');
		$oModuleModel = &getModel('module');
		
		$config = $oModuleModel->getModuleConfig('imageResize');
		$config->imageResize_use=( Context::get('imageresize_use')!='Y') ? 'N' : 'Y';
		$config->imageResize_width=Context::get('imageresize_width');
        $config->target_mid = str_replace('|@|',';',trim(Context::get('mid_list')));
        
		$oModuleController->insertModuleConfig('imageResize', $config);
		
		return new Object(0,"success_updated");
	}
}
?>