

<div class="app_list">
	
	<div class="app_list_header">
		<?=$client['Client']['street']?> <?=$client['Client']['homenumber']?><?=$client['Client']['addition']?>
	</div>
	
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
				}
				if(!empty($document['Document']['subtype']))
					$type_display .= ' - ' . tl(ucwords($document['Document']['subtype']));
				
				$document_folder = 'https://admin.drs-infra.nl/assets/documents/dgt/' . $document['Document']['project_list_id'] . '/';
				if($document['Document']['type'] == 'sketch')
				{
					$type_display = tl('Schets');
					$document_folder = 'https://admin.drs-infra.nl/assets/documents/sketches/' . $document['Document']['project_list_id'] . '/';
				}
				if($document['Document']['type'] == 'nestor')
				{
					$type_display = tl('Nestor formulier');
					$document_folder = 'https://admin.drs-infra.nl/assets/documents/nestor_reports/' . $document['Document']['project_list_id'] . '/';
				}
	?>
	<a href="/pdf?filename=<?=$document_folder?><?=$document['Document']['filename']?>&back=/clients/documents/<?=$client['Client']['id']?>" class="app_list_item">
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
		
		if(count($street_documents) > 0)
		{
	?>
	<div class="app_list_header">
		<?=ucfirst($client['Client']['street'])?>
	</div>
	<?php
			foreach($street_documents as $document)
			{
				$type_display = '';
				switch($document['Document']['type'])
				{
					case 'dgt':
						$type_display = tl('DGT rapport');
					break;
				}
				if(!empty($document['Document']['subtype']))
					$type_display .= ' - ' . tl(ucwords($document['Document']['subtype']));
				
				$document_folder = 'https://admin.drs-infra.nl/assets/documents/dgt/' . $document['Document']['project_list_id'] . '/';
				if($document['Document']['type'] == 'sketch')
				{
					$type_display = tl('Schets');
					$document_folder = 'https://admin.drs-infra.nl/assets/documents/sketches/' . $document['Document']['project_list_id'] . '/';
				}
				if($document['Document']['type'] == 'nestor')
				{
					$type_display = tl('Nestor formulier');
					$document_folder = 'https://admin.drs-infra.nl/assets/documents/nestor_reports/' . $document['Document']['project_list_id'] . '/';
				}
	?>
	<a href="/pdf?filename=<?=$document_folder?><?=$document['Document']['filename']?>&back=/clients/documents/<?=$client['Client']['id']?>" class="app_list_item">
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
		}
		
		
		if(count($project_documents) > 0)
		{
	?>
	<div class="app_list_header">
		<?=tl('Overige documenten')?>
	</div>
	<?php
			foreach($project_documents as $document)
			{
				$type_display = '';
				switch($document['Document']['type'])
				{
					case 'dgt':
						$type_display = tl('DGT rapport');
					break;
				}
				if(!empty($document['Document']['subtype']))
					$type_display .= ' - ' . tl(ucwords($document['Document']['subtype']));
				
				$document_folder = 'https://admin.drs-infra.nl/assets/documents/dgt/' . $document['Document']['project_list_id'] . '/';
				if($document['Document']['type'] == 'sketch')
				{
					$type_display = tl('Schets');
					$document_folder = 'https://admin.drs-infra.nl/assets/documents/sketches/' . $document['Document']['project_list_id'] . '/';
				}
				if($document['Document']['type'] == 'nestor')
				{
					$type_display = tl('Nestor formulier');
					$document_folder = 'https://admin.drs-infra.nl/assets/documents/nestor_reports/' . $document['Document']['project_list_id'] . '/';
				}
	?>
	<a href="/pdf?filename=<?=$document_folder?><?=$document['Document']['filename']?>&back=/clients/documents/<?=$client['Client']['id']?>" class="app_list_item">
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
		}
	?>
	
	<!--
	<a href="/pdf?filename=/files/1234 ab 1 - sterkte nieuw.pdf" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="far fa-file"></span>
		</span>
		2394XL11 - Schets van een ding
	</a>
	
	<div class="app_list_header">
		De Genestetstraat
	</div>
	
	<a href="/" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="far fa-file"></span>
		</span>
		Genestetstraat - Bouwtekening
	</a>
	
	<div class="app_list_header">
		<?=tl('Overige documenten')?>
	</div>
	
	<a href="/" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="far fa-file"></span>
		</span>
		G-12 sterktebeproeving (2020-07-17 09-40-27)
	</a>
	
	<a href="/" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="far fa-file"></span>
		</span>
		G-12 sterktebeproeving (2020-07-17 09-40-27)
	</a>
	
	<a href="/" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="far fa-file"></span>
		</span>
		G-12 sterktebeproeving (2020-07-17 09-40-27)
	</a>
	
	<a href="/" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="far fa-file"></span>
		</span>
		G-12 sterktebeproeving (2020-07-17 09-40-27)
	</a>
	
	<a href="/" class="app_list_item">
		<span class="app_list_item_prepend">
			<span class="far fa-file"></span>
		</span>
		G-12 sterktebeproeving (2020-07-17 09-40-27)
	</a>
	-->
</div>

<div style="clear: both;"></div>

<div class="app_center">
	<a href="/clients/details/<?=$client['Client']['id']?>"><?=tl('Terug naar woning')?></a>
</div>

<br /><br />

<script>
$(document).ready(function()
{
	back_button('<?=tl('Woning')?>', '/clients/details/<?=$client['Client']['id']?>');
});
</script>