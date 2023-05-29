<?php global $controller; ?>
<h1>Uploads verwijderen</h1>
<h5>Bestanden uit de map /files/ verwijderen</h5>

<div class="page_actions">
	<a class="btn" href="/project_lists/uploads"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar uploads</a>
</div>

<?php
	
	if($controller['get']['filecount'] > 0)
	{
		$files = explode(',', $controller['get']['files']);
		
		if($controller['get']['filecount'] == count($files))
		{
?>
<div class="error">
	Weet je zeker dat je de volgende <?=count($files)?> bestanden wilt verwijderen?
	<br /><br />
    <?php
	foreach($files as $file)
	{
		print('/files/' . $file . '<br />');
	}
?>
</div>
<div class="page_actions page_actions_bottom">
	<a id="remove_files" class="btn btn-alert" href="/project_lists/remove_uploads/?filecount=<?=count($files)?>&files=<?=$controller['get']['files']?>&action=remove"><span class="fas fa-times-circle"></span>Bestanden verwijderen</a>
</div>
            <?php
		}
	}
?>

<style>
	.error
	{
		margin: 10px 0px;
		background-color: #FFEADF;
		color: #CD3401;
		border: #FFB697 solid 1px;
		box-sizing: border-box;
		padding: 8px 10px;
	}
</style>