<script>
	function DelCartAll() {
		ProductFrame.location.href = "cart.php?mode=cart_del_all";
	}

	function BuyGoods() {
		location.href = "cart.php?mode=buy";
	}

	function DelCart(c_idx) {
		ProductFrame.location.href = "cart.php?mode=cart_del&c_idx="+c_idx;
	}
</script>
<iframe src="" id="ProductFrame" name="ProductFrame" frameborder="0" width="0" height="0" encoding="UTF-8"></iframe>


		<img src="/images/pixel.gif" width="1" height="15" />

		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<td class="board_tit_style2" valign="bottom">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<col width="180" /><col width="1" /><col width="*" /><col width="1" /><col width="100" /><col width="1" /><col width="100" /><col width="1" /><col width="110"/><col width="1" /><col width="70"/>
					<tr>
						<td class="title_txt01" >사진</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">제품명</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">판매금액</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">수량</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">합계</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">비고</td>
						<!--<td class="board_line_style2"></td>
								<td class="title_txt01"></td>-->
					</tr>
				</table>
			</td>
		</table>

		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<col width="180" /><col width="1" /><col width="*" /><col width="1" /><col width="100" /><col width="1" /><col width="100" /><col width="1" /><col width="110"/><col width="1" /><col width="70"/>

			<?
				if ( !$rsCnt ) {
			?>
			<tr>
				<td align="center" height="50" >등록된 상품이 없습니다.</td>
				<!--<td bgcolor="#dbdbdb"></td>
						<td class="board_list_style2" align="center"></td>-->
			</tr>
			<tr>
				<td colspan="13" height="1" bgcolor="#dbdbdb"></td>
			</tr>
			<?
				}
				$show_price = 0;
				$total_price = 0;
				$delivery_flag = false;
				while ( $row = $dbcon->fetch_array($rs) ) {
					//echo "c_pr_idx : ".$c_pr_idx."<BR>";

					// 상품정보 가져오기
					$PSQL = "
						select * from tbl_product
						where pr_idx = '".$row["c_pr_idx"]."'
					";
					$PRS = $dbcon -> fetch_array($dbcon -> query($PSQL));

					// 파일정보 가져오기
					$ObjFileName = "pr_img_thum";
					$ObjFileCnt = 1;
					$FileCnt = 0;
					if ( $row["c_pr_idx"] ) {
						$FileSQL = "
							select * from tbl_file
							where
								category = '".$ObjFileName."'
								and seq = '".$row["c_pr_idx"]."'
							order by idx desc
							limit 0, ".$ObjFileCnt."
						";
						$FileRS = $dbcon -> query($FileSQL);
						$FileCnt = $dbcon -> num_rows($FileRS);
					}

					$FileImg = "";
					if ( $FileCnt ) {
						$i = 0;
						if ( $FileRow = $dbcon->fetch_array($FileRS) ) {
							$FileImg = "<img src=\"".$url_product_data.$FileRow["file_name"].".thumb_160"."\">";
							$i++;
						}
					}

					// 옵션처리
					$print_option = "";
					$option_price = 0;
					$pr_option = $row["c_pr_option"];
					if ( $pr_option ) {
						$Arr_pr_option = explode("|",$pr_option );
						for ($i = 0 ; $i < count($Arr_pr_option); $i++ ) {
							$Arr_pr_option[$i] = explode("@",$Arr_pr_option[$i] );
							$Arr_pr_option2[0] = $Arr_pr_option[$i][0];
							$Arr_pr_option[$i] = explode("^", $Arr_pr_option[$i][1] );
							$Arr_pr_option2[1] = $Arr_pr_option[$i][0];
							$Arr_pr_option2[2] = $Arr_pr_option[$i][1];

							$ArrOpt2 = explode("/", $Arr_pr_option2[2]); //([0] : 옵션명, [1] : 추가금액)
							if ( $i > 0 ) {
								$print_option .= "/";
							}
							$print_option .= $ArrOpt2[0];
							$option_price = $option_price + $ArrOpt2[1];
						}
					}
//						echo "pr_option : ".$pr_option."<BR>";
//						echo "print_option : ".$print_option."<BR>";
//						echo "option_price : ".make_price_format($option_price)."<BR>";


					$PRS["pr_price"] = $PRS["pr_price"] + $option_price;

					$show_price = make_price_format($PRS["pr_price"]) * make_price_format($row["c_goods_num"]);
					if ( $PRS["pr_free_delivery"] ) $delivery_flag = true;
					$total_price += $show_price;
			?>

			<tr>
				<td align="center" height="100" ><?=$FileImg?></td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center">
					<a href="<?=$url_product?>product_view.php?pr_idx=<?=$row["c_pr_idx"]?>&pc_num=<?=$PRS["pc_num"]?>" target="_blank">
					<?=$PRS["pr_name"]?>
					<div><?=$print_option?></div>
					</a>
				</td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2 " align="center"><?=make_price_format($PRS["pr_price"],1)?> 원</td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center">
					<? if ( $mode == "list" ) { ?>
					<input type="text" size="5" class="input1" name="goods_num" value="<?=$row["c_goods_num"]?>" <?=$OnlyNum?> maxlength="10" />
					<!-- 상품 수량 수정 -->
					<a href="#"><img src="/images/99_common/btn_modi.gif" align="absmiddle"></a>
					<? } else { ?>
					<?=$row["c_goods_num"]?>
					<? } ?>
				</td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center"><?=make_price_format($show_price,1)?> 원</td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center">

					<a href="javascript:DelCart('<?=$row["c_idx"]?>')"><img src="/images/99_common/btn_del.gif"></a>
				</td>
				<!--<td bgcolor="#dbdbdb"></td>
						<td class="board_list_style2" align="center"></td>-->
			</tr>
			<tr>
				<td colspan="13" height="1" bgcolor="#dbdbdb"></td>
			</tr>
			<input type="hidden" name="pr_idx[]" value="<?=$row["c_pr_idx"]?>"><!-- 상품 번호 -->
			<input type="hidden" name="pr_img[]" value="<?=$url_product_data.$FileRow["file_name"].".thumb_160"?>"><!-- 상품 이미지 -->
			<input type="hidden" name="pr_name[]" value="<?=$PRS["pr_name"]?>"><!-- 상품 이름 -->
			<input type="hidden" name="print_option[]" value="<?=$print_option?>"><!-- 옵션명 -->
			<input type="hidden" name="pr_price[]" value="<?=$PRS["pr_price"]?>"><!-- 상품 가격 -->
			<input type="hidden" name="goods_num[]" value="<?=$row["c_goods_num"]?>"><!-- 주문수량 -->
			<input type="hidden" name="show_price[]" value="<?=$show_price?>"><!-- 상품 개별 합계가격 -->
			<?
				} // end while
				//unset ($rs);
			?>
		</table>


			<?

				// 배송료 설정
				if ( $delivery_flag ) {
					$delivery_price = 0;			// 배송료
				}
				else {
					if ( $total_price >= $sc_delivery_free) {
						$delivery_price = 0;
					}
					else {
						$delivery_price = $sc_delivery_money;
					}
				}
				$buy_price = $total_price + $delivery_price ;
				if ( $rsCnt ) {
			?>

		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td height="30" align="right">
					배송료  :  <?=make_price_format($delivery_price,1)?> 원    +   구입총액  :  <?=make_price_format($total_price,1)?> 원   =   <font color="#ff0000"><b>총 결제금액 : <?=make_price_format($buy_price,1)?> 원</b></font>
				</td>
			</tr>
		</table>
			<?
				}
			?>
			<input type="hidden" name="delivery_price" value="<?=$delivery_price?>"><!-- 배송료 -->
			<input type="hidden" name="total_price" value="<?=$total_price?>"><!--  상품 총 합계가격 -->
			<input type="hidden" name="buy_price" value="<?=$buy_price?>"><!-- 결제 번호 -->




		<? if ( $mode == "list" ) { ?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:2px 0 5px 0;">
			<tr>
				<td height="2" bgcolor="#8fa6c6"></td>
			</tr>
		</table>
		<br/>

		<!-- 버튼-->
		<div style="text-align:center;">
			<!-- 쇼핑 계속하기 -->
			<a href="<?=$url_product?>shop.php"><img src="/images/99_common/btn_shopping_go.gif"></a>
			<!-- 구매하기 -->
			<a href="javascript:BuyGoods();"><img src="/images/99_common/btn_buy_it.gif"></a>
			<!-- 장바구니 비우기 -->
			<a href="javascript:DelCartAll()"><img src="/images/99_common/btn_empty.gif"></a>

		</div>
		<? } ?>


