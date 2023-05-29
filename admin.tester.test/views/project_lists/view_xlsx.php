<?php global $controller; ?>
<h1><?=$controller['get']['filename']?></h1>
<h5>Aangemaakt op <?=date('d-m-Y H:i:s', filemtime('files/' . $controller['get']['filename']))?></h5>

<div class="page_actions">
	<a class="btn" href="/project_lists/uploads"><span class="fas fa-arrow-alt-circle-left"></span>Terug naar overzicht</a>
</div>

<table>
	<?php
		foreach($data['data'] as $index => $row)
		{
	?>
	<tr>
		<?php
			foreach($row as $value)
			{
		?>
		<?=($index == 0 ? '<th>' : '<td>')?>
			<?=$value?>
		<?=($index == 0 ? '</th>' : '</td>')?>
		<?php
			}
		?>
	</tr>
	<?php
		}
	?>
</table>