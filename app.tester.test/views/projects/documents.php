

<div class="app_list">
	
	<?php
		if(count($documents) > 0)
		{
			foreach($documents as $document)
			{
				$type_display = '';
				switch($document['Document']['type'])
				{
					case 'dgt':
						$type_display = tl('DGT rapport');
					break;
					case 'sketch':
						$type_display = tl('Schets');
					break;
					case 'nestor':
						$type_display = tl('Nestor formulier');
					break;
				}
				if(!empty($document['Document']['subtype']))
					$type_display .= ' - ' . tl(ucwords($document['Document']['subtype']));
				
				$document_folder = 'https://admin.drs-infra.nl/assets/documents/dgt/' . $document['Document']['project_list_id'] . '/';
				if($document['Document']['type'] == 'sketch')
					$document_folder = 'https://admin.drs-infra.nl/assets/documents/sketches/' . $document['Document']['project_list_id'] . '/';
				if($document['Document']['type'] == 'nestor')
					$document_folder = 'https://admin.drs-infra.nl/assets/documents/nestor_reports/' . $document['Document']['project_list_id'] . '/';
	?>
	<a href="/pdf?filename=<?=$document_folder?><?=$document['Document']['filename']?>&back=/projects/documents/<?=$project_list['Project_list']['id']?>" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="far fa-file"></span>
		</span>
		<div class="double_row">
			<?=$document['Document']['filename']?><br/ >
			<?=$type_display?>
		</div>
	</a>
	<?php
			}
		}else
		{
	?>
	<a class="app_list_item">
		<?=tl('Geen documenten')?>
	</a>
	<?php
		}
	?>
	
</div>

<div style="clear: both;"></div>

<br /><br />

<script>
$(document).ready(function()
{
	back_button('<?=tl('Terug')?>', '/projects/overview');
});
</script>