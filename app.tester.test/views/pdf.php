

<?php

redirect(($controller['get']['filename']));
/*
if($controller['iphone'] && false)
{
?>
<iframe id="pdf_frame" src="<?=($controller['get']['filename'])?>" style="width:100vw; height:calc(100vh - 120px);" frameborder="0"></iframe>
<?php

}else
{
?>
<iframe src="https://docs.google.com/gview?pid=explorer&efh=false&a=v&chrome=false&embedded=true&url=<?=urlencode($controller['get']['filename'])?>" style="width:100vw; height:calc(100vh - 120px);" frameborder="0"></iframe>
<?php
}
*/
?>


<script>
$(document).ready(function()
{
	back_button('<?=tl('Terug')?>', '<?=(!empty($controller['get']['back']) ? $controller['get']['back'] : '/')?>');
});
</script>