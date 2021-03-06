<?
$elemList = $this->Form->Header($this->form);
$html = array();
$filter = '';

foreach($elemList as $k => $row){

	if( $this->Form->continue[$k] )
		continue;

	$exp = explode('[', $row['name']);
	$name = $exp[0];

	$html[$name] = 
		'<div class="filter_helper filter_helper_header filter_helper_'.$name.'" rel="'.$name.'">'.
			'<div class="fa fa-close close"></div>'.
			$this->Form->tr($elemList, $k, $row).
			(isset($_GET[$name]) ? 
			'<div class="form-group">'.
				'<label for="filterElemSubmit'.$k.'"></label>'.
				'<input type="submit" name="filter" id="filterElemSubmit'.$k.'" class="btn btn-primary form-control" value="'._tr('Search').'"/> <a href="" class="remove">'._tr('Clear').'</a>'.
			'</div>'
			:
			'<div class="form-group">'.
				'<label for="filterElemSubmit'.$k.'"></label>'.
				'<input type="submit" name="filter" id="filterElemSubmit'.$k.'" class="btn btn-primary form-control" value="'._tr('Search').'"/>'.
			'</div>').
		'</div>';
	$filter .= $filter ? ','.$name : $name;
}
\Template\Template::includes();
?>

<script type="text/javascript">
$(document).ready(function(){

	$("<?=$filter;?>").HelperFilter("<?=addslashes(json_encode($html));?>");
});
</script>