<h1>Dashboard</h1>
<h5></h5>

<br /><br />

<?php
	
	$disk_space_total = 150;
	$disk_space_total = round(disk_total_space(dirname(__FILE__)) / 1024 / 1024 / 1024); 
	$disk_space_left = round(disk_free_space(dirname(__FILE__)) / 1024 / 1024 / 1024);
	$disk_space_used =round( $disk_space_total - $disk_space_left);
	if($disk_space_used > $disk_space_total)
		$disk_space_used = $disk_space_total;
	
	$percent = (($disk_space_used / $disk_space_total) * 100);
	$text = $disk_space_used . ' GB / ' . $disk_space_total . ' GB gebruikt (' .  round($percent) . ' %)';
?>

<b>Gebruikte schijfruimte server:</b><br /><br />
<div class="bar_chart_holder">
	<div class="bar_chart_text_below"><?=$text?></div>
	<div class="bar_chart_bar" style="width: <?=$percent?>%; ">
		<div class="bar_chart_text_above"><?=$text?></div>
	</div>
</div>

<style>
.bar_chart_holder
{
	width: 400px;
	height: 24px;
	border: #eeeeee solid 1px;
	position: relative;
}
.bar_chart_text_below
{
	position: absolute;
	left: 0px;
	top: 0px;
	height: 24px;
	width: 100%;
	text-align: center;
	line-height: 24px;
	overflow: hidden;
}
.bar_chart_bar
{
	position: absolute;
	left: 0px;
	top: 0px;
	background-color: rgba(24, 244, 0, 1);
	height: 24px;
	overflow: hidden;
}
.bar_chart_text_above
{
	position: absolute;
	left: 0px;
	top: 0px;
	height: 24px;
	width: 400px;
	text-align: center;
	line-height: 24px;
	overflow: hidden;
	color: #ffffff;
}
<style>