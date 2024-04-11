<?php
/**
 * @class  imageResizeAdminView
 * @author dexter (flavorkey@gmail.com)
 * @brief  imageResize 모듈의 admin view 클래스
 **/

class imageResizeAdminView extends imageResize {

	/**
	 * @brief 초기화
	 **/
	function init() {
		$this->setTemplatePath($this->module_path.'tpl');
	}

	/**
	 * @brief 관리 페이지 보여줌
	 **/
	function dispImageResizeAdminIndex() {
		$env=true;
		$code=0;

		//php 버전확인
		list($major,$minor,$extra)=explode('.',phpversion());
		list($release)=explode('-',$extra);
		//$major=3;$minor=3;$release=0;
		if($major<4 || ($major==4&& $minor<3)) {
			$env=false;
			$env_error->msg="php";
			$env_error->info=array($major,$minor,$release);
		}
		if(!$env) {
			Context::set("env_error",$env_error);
			$this->setTemplateFile('NotAllowd');
			return;
		}
		
		//gd 버전확인
		if (!extension_loaded('gd')) {
			$env=false;
			$env_error->msg="gd_load";
			$env_error->info="not installed or loaded";
		} else {
			if(!function_exists('imagecreatetruecolor')) 	{$env=false;$code+=1;}
			if(!function_exists('imagecreatefromjpeg')) 	{$env=false;$code+=2;}
			if(!function_exists('imagecreatefromgif')) 		{$env=false;$code+=4;}
			if(!function_exists('imagecreatefrompng')) 	{$env=false;$code+=8;}
			if(!function_exists('imagecopyresampled')) 	{$env=false;$code+=16;}
			if(!function_exists('imagejpeg')) 					{$env=false;$code+=32;}
			if(!function_exists('imagegif')) 						{$env=false;$code+=64;}
			if(!function_exists('imagepng')) 					{$env=false;$code+=128;}
			if(!env) {
				$env_error->msg="gd_version";
				$env_error->code=sprintf("%08s",decbin($code)); //최대255
				$env_error->info=gd_info();
			}
		}
		if(!$env) {
			Context::set("env_error",$env_error);
			$this->setTemplateFile('NotAllowd');
			return;
		}

		$oModuleModel = &getModel('module');

		$imageResize_info=$oModuleModel->getModuleConfig('imageResize');
		$target_mid=explode(";",$imageResize_info->target_mid);
		$imageResize_info->target_mid=$target_mid;

		Context::set("imageResize_info",$imageResize_info);

		// 모듈 카테고리 목록을 구함
		$module_categories = $oModuleModel->getModuleCategories();

		$mid_list = $oModuleModel->getMidList();

		// module_category와 module의 조합
		if($module_categories) {
			foreach($mid_list as $module_srl => $module) {
				$module_categories[$module->module_category_srl]->list[$module_srl] = $module;
			}
		} else {
			$module_categories[0]->list = $mid_list;
		}

		Context::set('mid_list',$module_categories);

		// 템플릿 파일 지정
		$this->setTemplateFile('adminindex');
	}
}
?>
