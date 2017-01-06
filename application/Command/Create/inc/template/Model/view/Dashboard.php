<div class="label"><?=$this->Language('{namespace}');?> <a href="<?=$this->url(array('model' => '{namespace}'));?>"><?=$this->Language('manage');?></a></div>
<div class="content">
	<table width="100%">
	<tbody>
	<?
	//foreach(\Conf\Conf::LANGUAGE as $row){
	?>
	<tr>
		<td><a href="<?=$this->url(array('model' => '{namespace}'));?>"><?=$row;?></a>
			</td>
	</tr>
	<?
	//}
	?>
	</tbody>
	</table>
</div>