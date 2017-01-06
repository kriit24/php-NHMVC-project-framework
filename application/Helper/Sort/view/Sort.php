<?

$html = array();

foreach($this->sort as $v){

	$html[$v] = 
		'<div class="sort_helper sort_helper_'.$v.'">'.
		'<img src="'.$this->toUrl( __DIR__ ).'/../image/sort_black.png"/>'.
		'</div>';
}

$sort = '';
foreach($this->sort as $v){

	$sort .= $sort ? ','.$v : $v;
}

$this->script('$("'.$sort.'").HelperSort("'.addslashes(json_encode($html)).'");');

?>