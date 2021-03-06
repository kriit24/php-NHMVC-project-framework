<?
Model\Dashboard\Index::singleton()->title(
	_tr('Privilege'), 
	array()
);

while($row = $this->role->fetch()){

	?>
	<div class="ibox float-e-margins">
		<div class="ibox-title dropdown" for="privilege-<?=$row['id'];?>">
		    <h5><b><?=($row['name']);?></b></h5>

		    <div class="ibox-tools">
				<a class="collapse-link">
					<i class="fa fa-chevron-down"></i>
				</a>
			</div>
		</div>

		<div class="ibox-content border privilege-<?=$row['id'];?>" style="<?= isset( $_POST['privilege'][ $row['id'] ] ) ? '' : 'display: none;' ?>width:95%;">

			<form method="post">
			<div style="margin-bottom:20px;">
			<h5 style="border-bottom:1px solid #025AA5;padding-bottom:10px;"><b><?=_tr( 'Select all' );?></b></h5>
			<div>
				<div style="display:inline-block;vertical-align:top;padding:10px;">
					<div style="margin-bottom:5px;">
						<input type="checkbox" name="privilege[<?=($row['id']);?>][all]" value="1" id="<?=($row['id']);?>_all" class="select-all"> 
						<label for="<?=($row['id']);?>_all" class="checkbox"><span></span><?=_tr( 'Select' );?></label>
					</div>
				</div>
			</div>
			</div>
			<?
			foreach(\Model\Privilege\Form::ROUTE as $route){

				$classes = $this->getClassListing($route);
				if( !empty($classes) ){

					?>
					<div style="margin-bottom:20px;">
					<h5 style="border-bottom:1px solid #025AA5;padding-bottom:10px;"><b><?=$route;?></b></h5>
					<div>
					<?
					foreach($classes as $class){

						?>
						<div style="display:inline-block;vertical-align:top;padding:10px;" class="subitoggle">
							<div style="margin-bottom:10px;"><b><?=$class['class'];?></b></div>

							<div style="margin-bottom:5px;">
								<input type="checkbox" name="privilege[<?=($row['id']);?>][all]" value="1" id="<?=($row['id']);?>_<?=($route);?>_<?=($class['class']);?>_<?=($method);?>_group_all"> 
								<label for="<?=($row['id']);?>_<?=($route);?>_<?=($class['class']);?>_<?=($method);?>_group_all" class="checkbox select-group"><span></span><?=_tr( 'Select all' );?></label>
							</div>
							<?
							foreach($class['methods'] as $method){

								$checked = isset( $this->privileges[ $row['id'] ][ $route ][ $class['class'] ][ $method ] ) ? ' checked="checked"' : '';
								?>
								<div style="margin-bottom:5px;">
									<input type="checkbox" name="privilege[<?=($row['id']);?>][<?=($route);?>][<?=($class['class']);?>][<?=($method);?>]" value="1" id="<?=($row['id']);?>_<?=($route);?>_<?=($class['class']);?>_<?=($method);?>"<?=($checked);?>> 
									<label for="<?=($row['id']);?>_<?=($route);?>_<?=($class['class']);?>_<?=($method);?>" class="checkbox"><span></span><?=$method;?></label>
								</div>
								<?
							}
							?>
						</div>
						<?
					}
					?>
					</div>
					</div>
					<?
				}
			}
			?>
			<input type="submit" name="updatePrivilege" value="<?=(_tr( 'Update' ));?>" class="btn btn-primary">
			</form>
		</div>
	</div>
	<?
}

?>