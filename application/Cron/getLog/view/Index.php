<?
foreach($this->subDir as $dir){

	echo '<a href="?subdir='.basename($dir).'">'.basename($dir).'</a><br>';
	if( $_GET['subdir'] && $_GET['subdir'] == basename($dir) ){

		echo '<p style="padding-left:5px;margin:0px;">';

		foreach($this->subFile as $file){

			echo '<a href="?subdir='.basename($dir).'&subfile='.basename($file).'">'.basename($file).'</a><br>';
			if( $_GET['subfile'] && $_GET['subfile'] == basename($file) ){

				echo '<p style="padding-left:15px;margin-top:0px;">';
				echo str_replace("\n", '<br>', $this->subFileContent);
				echo '</p>';
			}
		}
		echo '</p>';
	}
}
?>