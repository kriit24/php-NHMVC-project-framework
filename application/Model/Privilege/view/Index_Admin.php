<?
Model\Dashboard\Index::singleton()->title(
	$this->Language('Privilege'), 
	array()
);

while($row = $this->role->fetch()){

	?>
	<div class="ibox float-e-margins">
		<div class="ibox-title">
		    <h5><b><?=($row['name']);?></b></h5>

		    <div class="ibox-tools">
				<a class="collapse-link dropdown" id="privilege-<?=$row['id'];?>">
					<i class="fa fa-chevron-down"></i>
				</a>
			</div>
		</div>

		<div class="ibox-content border privilege-<?=$row['id'];?>" style="<?= isset( $_POST['privilege'][ $row['id'] ] ) ? '' : 'display: none;' ?>width:95%;margin:0 auto;">

			<form method="post">
			<div style="margin-bottom:20px;">
			<h5 style="border-bottom:1px solid #025AA5;padding-bottom:10px;"><b><?=$this->Language( 'Select all' );?></b></h5>
			<div>
				<div style="display:inline-block;vertical-align:top;padding:10px;">
					<div style="margin-bottom:5px;">
						<input type="checkbox" name="privilege[<?=($row['id']);?>][all]" value="1" id="<?=($row['id']);?>_all" class="select-all"> 
						<label for="<?=($row['id']);?>_all" class="checkbox"><span></span><?=$this->Language( 'Select' );?></label>
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
						<div style="display:inline-block;vertical-align:top;padding:10px;">
						<div style="margin-bottom:10px;"><b><?=$class['class'];?></b></div>
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
			<input type="submit" name="updatePrivilege" value="<?=($this->Language( 'Update' ));?>" class="btn btn-primary">
			</form>
		</div>
	</div>
	<?
}

?>