<?
if( is_Array($this->methods['content']) ){

	echo '<div class="dashboard-box">';

	foreach($this->methods['content'] as $className){

		$m = new $className;
		$m->Dashboard();
	}
	echo '</div>';
}
?>