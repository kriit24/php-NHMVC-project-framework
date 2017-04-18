<div class="dropdown-submenu">
	<?
	foreach($this->crons as $cron)
		echo '<a class="dropdown-item" href="'.$this->url(array('route' => 'Cron', 'cron' => basename($cron))).'">'.basename($cron).'</a>';
	?>
</div>