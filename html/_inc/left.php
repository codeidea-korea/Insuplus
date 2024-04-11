<!--L_LEFT_MENU[[[-->
<style>
    .sub_mn li {margin:0; padding:0;}
	.sub_mn h2{background:#218acc;padding:30px 0px 28px 30px;font-size:24px;color:#FFFFFF;font-weight:700;margin:0px;}
	.sub_mn .mn_list li{background-image:url('/html/_images/common/bg_left_off.png');border-bottom:1px solid #dcdcdc;padding:14px 0px 13px 30px;font-size:14px;font-weight:600;}
	.sub_mn .mn_list li a{color:#646464;}
	.sub_mn .mn_list li.on{background-image:url('/html/_images/common/bg_left_on.png');border-bottom:1px solid #dcdcdc;padding:14px 0px 13px 30px;color:#FFFFFF;font-size:14px;}
	.sub_mn .mn_list li.on a{color:#FFFFFF;}
</style>

<!--서브메뉴-->
<div class="sub_mn">
	<?if ($mn1=="product"){?>
	<h2>M&amp;N Product</h2>
	<ul class="mn_list" style="">
		<li <?if ($mn2=="1"){?>class="on"<?}?>><a href="product01.php">Automoblie</a></li>
		<li <?if ($mn2=="2"){?>class="on"<?}?>><a href="product02.php">Home appliance</a></li>
	</ul>
	<?}?>

	<?if ($mn1=="service"){?>
	<h2>M&amp;N Service</h2>
	<ul class="mn_list" style="">
		<li <?if ($mn2=="1"){?>class="on"<?}?>><a href="service01.php">Manufacture</a></li>
		<li <?if ($mn2=="2"){?>class="on"<?}?>><a href="service02.php">Tool Trial</a></li>
		<li <?if ($mn2=="3"){?>class="on"<?}?>><a href="service03.php">Measuring & Inspection</a></li>
		<li <?if ($mn2=="4"){?>class="on"<?}?>><a href="service04.php">Total Solution</a></li>
	</ul>
	<?}?>

	<?if ($mn1=="about"){?>
	<h2>About us</h2>
	<ul class="mn_list" style="">
		<li <?if ($mn2=="1"){?>class="on"<?}?>><a href="about01.php">M&N Hi-tech is</a></li>
		<li <?if ($mn2=="2"){?>class="on"<?}?>><a href="about02.php">History</a></li>
		<li <?if ($mn2=="3"){?>class="on"<?}?>><a href="about03.php">R&amp;D Lab</a></li>
		<li <?if ($mn2=="4"){?>class="on"<?}?>><a href="about04.php">News and Event</a></li>
	</ul>
	<?}?>

	<?if ($mn1=="contact"){?>
	<h2>Contact us</h2>
	<ul class="mn_list" style="">
		<li <?if ($mn2=="1"){?>class="on"<?}?>><a href="contact01.php">Location</a></li>
		<li <?if ($mn2=="2"){?>class="on"<?}?>><a href="contact02.php">Q&amp;A</a></li>
	</ul>
	<?}?>

	<?if ($mn1=="sitemap"){?>
	<h2>SiteMap</h2>
	<ul class="mn_list" style="">
		<li <?if ($mn2=="1"){?>class="on"<?}?>><a href="sitemap.php">SiteMap</a></li>
	</ul>
	<?}?>
	<!--하단 배너-->

	<div class="sub_bn" style="margin-top: 20px;">
		<ul style="height:115px;">
			<li style="float:left;"><a href="../product/product01.php"><img src="/html/_images/common/ic_left_auto.png" alt="Automobile"/></a></li>
			<li style="float:left;"><a href="../product/product02.php"><img src="/html/_images/common/ic_left_home.png" alt="Home appliance"/></a></li>
		</ul>
		<ul style="height:77px;">
			<li style="float:left;"><a href="<?=$fb_url?>" target="_blank"><img src="/html/_images/common/ic_sns_fb.png" alt="facebook"/></a></li>
			<li style="float:left;"><a href="<?=$tw_url?>" target="_blank"><img src="/html/_images/common/ic_sns_tw.png" alt="tweeter"/></a></li>
			<li style="float:left;"><a href="<?=$insta_url?>" target="_blank"><img src="/html/_images/common/ic_sns_insta.png" alt="instagram"/></a></li>
		</ul>
	</div>

</div>
<!--L_LEFT_MENU]]]-->