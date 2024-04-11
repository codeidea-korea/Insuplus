				<!--L_TOP_MENU[[[-->
				<style type="text/css">
				#navi { width:620px; height:77px;}
				.navi_main { width:620px; height:53px; position:relative; top: 29px; left:70px;z-index:10;}
				.navi_main a { padding-right:50px;font-size:19px; letter-spacing:-0.5px;}
				.navi_sub_1 { display:block; position:relative; top:40px; left: 60px; padding:0px 0px 0px 0px; vertical-align:middle; height:24px; width:300px;z-index:10;}
				.navi_sub_1 a { padding-right: 8px; color:#FFFFFF; }
				.navi_sub_2 { display:block; position:relative; top:40px; left:180px; padding:0px 0px 0px 0px; vertical-align:middle; height:24px; width:400px;z-index:10;}
				.navi_sub_2 a { padding-right: 8px; color:#FFFFFF;}
				.navi_sub_3 { display:block; position:relative; top:40px; left:310px; padding:0px 0px 0 0px; vertical-align:middle; height:24px; width:350px;z-index:10;}
				.navi_sub_3 a { padding-right: 8px; color:#FFFFFF;}
				.navi_sub_4 { display:block; position:relative; top:40px; left:540px; padding:0px 0px 0 0px; vertical-align:middle; height:24px; width:210px;z-index:10;}
				.navi_sub_4 a { padding-right: 8px; color:#FFFFFF;}
				</style>
				<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery('#navi .navi_main .navi_main_obj').each(function(){
						if(!jQuery(this).attr('orgsrc')) {
							jQuery(this).attr('orgsrc', jQuery(this).attr('src'));
						}

						jQuery(this).hover(function(){
							_fcNaviControl(jQuery(this).attr('subcode'));
						});
					});

					var _fcNaviControl = function(selCode) {

					if(!selCode) return;

					jQuery('#navi .navi_main .navi_main_obj').each(function(){
						jQuery('#navi .navi_sub_'+jQuery(this).attr('subcode')).hide();
						jQuery(this).attr('src', jQuery(this).attr('orgsrc'));

					});

					jQuery('#navi .navi_main .navi_main_obj[subcode='+selCode+']').attr('src', jQuery('#navi .navi_main .navi_main_obj[subcode='+selCode+']').attr('oversrc'));
					jQuery('#navi .navi_sub_'+selCode).show();

					};
				});


				$load(function() {		// 최종로딩
					$l($qList('#navi .navi_main .navi_main_obj'), 'mouseover', function (evt, obj) {
						Event.stop(evt);

						var selCode = obj.getAttribute('subcode');
						var subCode = null;

						$qList('#navi .navi_main .navi_main_obj').each(function(obj){
							subCode = obj.getAttribute('subcode');

							if( selCode == subCode ) {
								if( $q('.navi_sub_'+subCode) ) {
									Element.show($q('#navi .navi_sub_'+subCode));
								}
							} else {
								if( $q('.navi_sub_'+subCode) ) {
									Element.hide($q('#navi .navi_sub_'+subCode));
								}
							}
						});
					});
				});
				</script>

				<div id="navi">
					<div class="navi_main">
						<a href="../product/product01.php" class='navi_main_obj' subcode='1' <?if ($mn1=="product"){?>style='color:#0b96d4;'<?}?>>메뉴1</a>
						<a href="../service/service01.php" class='navi_main_obj' subcode='2' <?if ($mn1=="service"){?>style='color:#0b96d4;'<?}?>>메뉴2</a>
						<a href="../about/about01.php" class='navi_main_obj' subcode='3' <?if ($mn1=="about"){?>style='color:#0b96d4;'<?}?>>메뉴3</a>
						<a href="../contact/contact01.php" class='navi_main_obj' subcode='4' <?if ($mn1=="contact"){?>style='color:#0b96d4;'<?}?>>메뉴4</a>
					</div>
					<div class="navi_sub_1" <?if ($mn1=="product"){?><?}else{?>style='display:none;'<?}?>>
						<a href="../product/product01.php">Automobile</a>
						<a href="../product/product02.php">Home appliance</a>
					</div>
					<div class="navi_sub_2" <?if ($mn1=="service"){?><?}else{?>style='display:none;'<?}?>>
						<a href="../service/service01.php">Manufacture</a>
						<a href="../service/service02.php">Tool Trial</a>
						<a href="../service/service03.php">Measuring and Inspection</a>
						<a href="../service/service04.php">Total Solution</a>
					</div>
					<div class="navi_sub_3" <?if ($mn1=="about"){?><?}else{?>style='display:none;'<?}?>>
						<a href="../about/about01.php">insplus is</a>
						<a href="../about/about02.php">History</a>
						<a href="../about/about03.php">R&amp;D Lab</a>
						<a href="../about/about04.php">News and Event</a>
					</div>
					<div class="navi_sub_4" <?if ($mn1=="contact"){?><?}else{?>style='display:none;'<?}?>>
						<a href="../contact/contact01.php">Location</a>
						<a href="../contact/contact02.php">QnA</a>
					</div>
				</div>
				<!--//네비--><!--L_TOP_MENU]]]-->