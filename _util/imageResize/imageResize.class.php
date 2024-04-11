<?php
/**
 * @class  file
 * @author dexter (flavorkey@gmail.com)
 * @brief  imageResize 모듈의 high 클래스
 **/

class imageResize extends ModuleObject {
	/**
	 * @brief 설치시 추가 작업이 필요할시 구현
	 **/
	function moduleInstall() {
		return new Object();
	}
	
	/**
	 * @brief 설치가 이상이 없는지 체크하는 method
	 **/
	function checkUpdate() {
		$oModuleModel = &getModel('module');

		//설정파일
		$config = $oModuleModel->getModuleConfig('imageResize');
		$module_info = $oModuleModel->getModuleInfoXml('imageResize');
		if(!$config->version || ($config->version<$module_info->version)) return true;

		return false;
	}

	/**
	 * @brief 업데이트 실행
	 **/
	function moduleUpdate() {
		$oModuleController = &getController('module');
		$oModuleModel = &getModel('module');

		$module_info = $oModuleModel->getModuleInfoXml('imageResize');

		$config->version=$module_info->version;
		$config->imageResize_use='N';
		$config->imageResize_width='768';
		$config->target_mid='';

		$oModuleController->insertModuleConfig('imageResize', $config);

		// action forward에 등록 (관리자 모드에서 사용하기 위함)
		$output=$oModuleController->insertActionForward('imageResize', 'view', 'dispImageResizeAdminIndex');
		if($output->getError()<0) return new Object(-1,'error');

		if(!$oModuleModel->getTrigger('file.insertFile', 'imageResize', 'controller', 'triggerInsertFile', 'after'))
		$oModuleController->insertTrigger('file.insertFile', 'imageResize', 'controller', 'triggerInsertFile', 'after');

		return new Object(0, 'success_updated');
	}

	/**
	 * @brief 캐시 파일 재생성
	 **/
	function recompileCache() {
	}
}
?>
