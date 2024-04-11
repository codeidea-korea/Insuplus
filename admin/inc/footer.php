						<!-- //Content End -->
					</td>

					<?
					if ($tm == "product" ) {
					?>
					<td valign="top">

						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td style="padding:5 0 10 5px;">
									<div style="height:600px; overflow-y:scroll; overflow-x:hidden'">
										<?include_once $path_admin."product/category.php";?>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2" height="1" bgcolor="#E5E5E5"></td>
							</tr>
						</table>
						<?
					    if ( getLen($search_pc_num) > 0 ) {
					        $pc_num_len = getLen($search_pc_num);
					        if ($pc_num_len > 2) {
					            echo "<script>menuclick('".getStrCut($search_pc_num, 2)."')</script>";
					        }
					        if ($pc_num_len > 4) {
					            echo "<script>menuclick('".getStrCut($search_pc_num, 4)."')</script>";
					        }
					    }
						?>


					</td>
					<?
					}
					else {
					?>

					<td>&nbsp;</td>
					<?
					}
					?>
				</tr>
			</table>

			<!-- Footer Start -->
			<p class="footerWrap">Copyright(c) KoreaAssistance. All Rights Reserved</p>
		</td>
	</tr>
</table>
<!-- Footer End -->
</body>
</html>
<?
$dbcon -> dbcon_close();
?>
