<?
\Model\Dashboard\Index::singleton()->title(
	$this->Language('Table')
);
?>

<table class="table table-hover table-horizontal">
<?

foreach($this->tables as $row){

	echo '<tr>';
		echo '<td>'.$row['name'].'</td>';
		echo '<td>'.($row['installed'] ? $this->Language('Installed') : '').'</td>';
		echo '<td>'.($row['installed'] ? '' : '<a href="'.$this->url(array('route' => 'Command', 'command' => 'Table', '?action=Install&table='.$row['name'])).'">'.$this->Language('Install').'</a>').'</td>';
		echo '<td>'.(!$row['column_matches'] ? '<a href="'.$this->url(array('route' => 'Command', 'command' => 'Table', '?action=Rebuild&table='.$row['name'])).'">'.$this->Language('Rebuild columns').'</a>' : '').'</td>';
	echo '</tr>';
}

?>
</table>