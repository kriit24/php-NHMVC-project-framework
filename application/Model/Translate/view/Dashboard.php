<div class="col">
	<table class="table">
		<thead>
			<tr>
				<th><?=_tr('Translate');?> <a href="<?=$this->url(array('model' => 'Translate'));?>"><?=_tr('manage');?></a></th>
			</tr>
		</thead>
		<tbody>
		<?
		foreach(\Conf\Conf::LANGUAGE as $row){
		?>
		<tr>
			<td><a href="<?=$this->url(array('id' => 1, 'model' => 'Translate', 'byLanguage' => $row));?>"><?=$row;?></a>
				</td>
		</tr>
		<?
		}
		?>
		</tbody>
	</table>
</div>