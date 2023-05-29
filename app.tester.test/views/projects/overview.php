
<div class="app_list">
	
	<?php
		foreach($project_lists as $project_list)
		{
	?>
	<a href="/projects/documents/<?=$project_list['Project_list']['id']?>" class="app_list_item">
		<span class="app_list_item_prepend pending">
			<span class="fas fa-circle"></span>
		</span>
		<div class="double_row">
			<b><?=$project_list['Project_list']['name']?></b><br />
			<?=count($project_list['documents'])?> Documenten
		</div>
		<div class="arrow_right"></div>
	</a>
	<?php
		}
	?>
	
</div>
