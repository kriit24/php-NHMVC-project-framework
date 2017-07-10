<div class="col">
	<table class="table">
		<thead>
			<tr>
				<th><?=_tr('User interface');?></th>
			</tr>
		</thead>
		<tbody>
		<tr class="content-row">
			<td><a href="<?=$this->url(array('model' => 'User'));?>"><?=_tr('Users');?></a>
				</td>
		</tr>
		<tr class="content-row">
			<td><a href="<?=$this->url(array('model' => 'Role'));?>"><?=_tr('Roles');?></a>
				</td>
		</tr>
		<tr class="content-row">
			<td><a href="<?=$this->url(array('model' => 'Privilege'));?>"><?=_tr('Privileges');?></a>
				</td>
		</tr>
		</tbody>
	</table>
</div>