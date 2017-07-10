<?
\Model\Dashboard\Index::singleton()->title(
	_tr('Table')
);
?>

<table class="table table-hover table-horizontal">
<?

foreach($this->tables as $row){

	echo '<tr>';
		echo '<td>'.$row['name'].'</td>';
		echo '<td>'.($row['installed'] ? _tr('Installed') : '').'</td>';
		echo '<td>'.($row['installed'] ? '' : '<a href="'.$this->url(array('route' => 'Command', 'command' => 'Table', '?action=Install&table='.$row['name'])).'">'._tr('Install').'</a>').'</td>';
		echo '<td>'.(!$row['column_matches'] ? '<a href="'.$this->url(array('route' => 'Command', 'command' => 'Table', '?action=Rebuild&table='.$row['name'])).'">'._tr('Rebuild columns').'</a>' : '').'</td>';
	echo '</tr>';
}

?>
</table>