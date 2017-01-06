<div class="col">
	<table class="table">
		<thead>
			<tr>
				<th><?=$this->Language('User interface');?></th>
			</tr>
		</thead>
		<tbody>
		<tr class="content-row">
			<td><a href="<?=$this->url(array('model' => 'User'));?>"><?=$this->Language('Users');?></a>
				</td>
		</tr>
		<tr class="content-row">
			<td><a href="<?=$this->url(array('model' => 'Role'));?>"><?=$this->Language('Roles');?></a>
				</td>
		</tr>
		<tr class="content-row">
			<td><a href="<?=$this->url(array('model' => 'Privilege'));?>"><?=$this->Language('Privileges');?></a>
				</td>
		</tr>
		</tbody>
	</table>
</div>