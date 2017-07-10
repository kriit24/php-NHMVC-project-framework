<?

if( $this->selfError['installed'] || $this->selfError['column_matches'] ){

	echo '<div class="table-background"></div>';
	echo '<div class="table-error"><a href="'.$this->url(array('route' => 'Command', 'command' => 'Table')).'">'._tr('Tables is not installed correctly').'</a></div>';
}

?>