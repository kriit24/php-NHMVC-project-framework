<div class="filter_helper_form filter_helper_form_group" style="text-align:center;">
<div class="filter_dropdown_box">
<div class="filter_dropdown_title"><span><?=_tr('Filter');?></span><i class="fa fa-sort-desc dropdown"></i></div>
<div class="filter_dropdown_content" style="<?=$_GET['filter'] ? 'display:block;' : '';?>">

<?
$formArray = $this->Form->group($this->form);
foreach($formArray as $elemList){

	echo '<div class="filter_group">';

	foreach($elemList as $k => $row){

		if( $this->Form->continue[$k] )
			continue;

		$exp = explode('[', $row['name']);
		$name = $exp[0];

		echo '<div class="filter_helper_show filter_helper_'.$name.'" rel="'.$row['name'].'">'.
			$this->Form->tr($elemList, $k, $row).
			'</div>';
	}
	echo '</div>';
}
?>
	<div class="form-group"><input type="submit" name="filter" class="btn btn-primary form-control" value="<?=_tr('Search');?>"> <a href="<?=($_SERVER['SCRIPT_URI'] ?? './');?>"><?=_tr('Clear');?></a></div>
</div>
</div>
</div>

<?
\Template\Template::includes();
?>

<script type="text/javascript">
$(document).ready(function(){

	$('.filter_helper_form_group').ClickFilter();
});
</script>