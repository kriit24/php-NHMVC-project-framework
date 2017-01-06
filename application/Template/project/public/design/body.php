<div class="container-fluid">
	<div class="container-fluid header-bar" id="header-bar">
		<div class="row">
			<div class="col col-nowrap"><? $this->content('header-left'); ?></div>
			<div class="col col-nowrap"><? $this->content('header'); ?></div>
			<div class="col col-nowrap"><? $this->content('header-right'); ?></div>
		</div>
	</div>
	<div class="container-fluid header-bar" id="header-bar-bottom">
		<div class="row">
			<div class="col"><? $this->content('header-bottom'); ?></div>
		</div>
	</div>

	<div class="container-fluid content-bar" id="content-bar">
		<div class="row">
			<div class="col"><? $this->content('content'); ?></div>
		</div>
	</div>

	<div class="container-fluid footer-bar" id="footer-bar">
		<?=date('Y');?> &copy; IT Project Partner OÜ
	</div>
</div>