<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

						</ul>

						<!-- 페이징 -->
						<div class="paging blankAreaTy01">
							<?
							if ($CmtTotalCnt>0){
								list_page_comment_ljh($page, $total_cnt_page, $page_per_block);
							}
							?>
						</div>
						<!-- // 페이징 -->

<?}else{
//관리자 모드입니다.
?>
</table>
<?}?>