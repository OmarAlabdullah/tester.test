<h1>Export Week <?=$week_number?> - <?=$year?></h1>
<h5><?=count($project_lists)?> projecten</h5>

<div class="page_actions">
	<a class="btn" href="/calendar/overview?year=<?=$year?>&week_number=<?=$week_number?>"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar agenda</a>
</div>

<br /><br />

<?php
	$number_of_files = 0;
	
	if(count($project_lists) > 0)
	{
		foreach($project_lists as $project_list)
		{
			if(file_exists($project_list['zip_filename']))
			{
?>
<a href="/<?=$project_list['zip_filename']?>" target="_blank" class="zip_link">
	<span class="fas fa-cloud-download-alt"></span>
	
	<span class="title"><?=$project_list['Project_list']['name']?></span>
	<br />
	<span class="subline"><?=count($project_list['clients'])?> addressen</span>
</a>
<br /><br />

<?php
				$number_of_files++;
			}
		}
	}
	if($number_of_files == 0)
	{
?>
<b>Geen data gevonden</b>
<?php
	}
?>

<style>
.zip_link
{
	display: inline-block;
	position: relative;
	padding: 20px;
	padding-left: 80px;
	background-color: rgba(0, 0, 0, 0.05);
	cursor: pointer;
	height: 30px;
	line-height: 15px;
	border-radius: 2px;
	min-width: 200px;
	color: #5d6267;
	text-decoration: none;
}
.zip_link .fas
{
	position: absolute;
	top: 20px;
	left: 20px;
	font-size: 30px;
}
.zip_link .title
{
	font-weight: 700;
}
.zip_link:hover
{
	color: #000000;
	background-color: rgba(0, 0, 0, 0.1);
}
</style>