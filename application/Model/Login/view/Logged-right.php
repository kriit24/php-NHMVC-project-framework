<div class="logged right">
<?=( \Session::userData()->first_name.' '.\Session::userData()->last_name );?> <a href="?action=logout"> <i class="fa fa-sign-out"></i> <?=_tr('Logout');?></a>
</div>