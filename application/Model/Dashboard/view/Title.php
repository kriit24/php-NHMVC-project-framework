<div class="dashboard">

	<div class="col col-width-auto">
		<h5>
			<?
			echo $this->Title;
			?>
		</h5>
	</div>
	<div class="col col-nowrap col-width-auto">
	<?
	foreach($this->Links as $link)
		echo $link;

	//echo ($this->ShowDashboard ? '<a href="/admin" class="btn btn-primary" style="margin-left:20px;">'._tr('Dashboard').'</a>' : '' );
	?>
	</div>
	<div style="clear:both;"></div>
</div>

<?
if( $this->GoWithScroll )
	$this->script('$(".dashboard").fixed_menu();');
?>