<div class="language-bar col-nowrap">
	<div class="language-bar-inner">
	<?
		foreach(\Conf\Conf::LANGUAGE as $key => $lang)
			echo '<a href="?language='.$lang.'"><span class="flag-icon flag-icon-'.$key.'"></span></a>';
	?>
	</div>
</div>