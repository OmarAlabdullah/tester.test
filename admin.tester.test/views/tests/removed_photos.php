<h1>Uploads</h1>
<h5>Alle bestanden in de map /var/www/vhosts/drs-infra.nl/app.drs-infra.nl/photos/</h5>

<table>
	<tr>
		<th class="check"><input type="checkbox" /></th>
		<th width="100">&nbsp;</th>
		<th>Project List id</th>
		<th>Bestandsnaam</th>
		<th>Grootte</th>
		<th>Gewijzigd op</th>
	</tr>
    <?php
		$total_removed = 0;
		foreach($data['files'] as $file)
		{
			$total_removed += $file['filesize'];
	?>
	<tr>
		<td class="check"><input type="checkbox" /></td>
		<td style="text-align: center; "><?=(!$file['in_db'] ? '<span style="color: #cc0000; font-weight: bold; ">DB</span>' : ($file['removed'] ? '<span style="color: #cc0000; " class="far fa-times-circle"></span>' : '&nbsp;'))?></td>
		<td><?=$file['project_list_id']?></td>
		<td><a href="https://app.drs-infra.nl/photos/<?=$file['project_list_id']?>/<?=$file['filename']?>" target="_blank"><?=$file['filename']?></a></td>
		<td><?=_kb($file['filesize'])?></td>
		<td><?=date('d-m-Y H:i:s', $file['filetime'])?></td>
	</tr>
            <?php
		}
	?>
	<tr>
		<td colspan="4"><b>Totaal verwijderd</b></td>
		<td colspan="2"><b><?=_kb($total_removed)?></b></td>
	</tr>
</table>

<script>
$(document).ready(function()
{
	
});
</script>