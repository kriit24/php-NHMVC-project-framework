<?
echo '<script type="text/javascript">var posted = false;</script>';
if( $_POST['addContent'] )
	echo '<script type="text/javascript">posted = true;</script>';
?>

<style type="text/css" title="">
<?=file_get_contents( dirname(__DIR__). '/inc/layout.copy.css' );?>
</style>

<script type="text/javascript">
var Project = { 'textSelect' : false };
</script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js" type="text/javascript"></script>
<script src="/Template/js/project.storage.js" type="text/javascript"></script>

<script type="text/javascript">
<?=file_get_contents( dirname(__DIR__). '/inc/layout.copy.js' );?>
</script>

<div class="layout-content-copying-background" style="<?=($_POST['addContent'] ? 'display:block;' : 'display:none;');?>"></div>
<div class="layout-content-copying" style="<?=($_POST['addContent'] ? 'display:block;' : 'display:none;');?>">
	<div><?= ($this->Language( 'Copying ...' )); ?></div>
</div>

<div class="layout-content">
<?

$form = new \Library\Form('list');
$form->addElem('form', '', array(
	'' => ''
));

$form->addElem('text', 'menu', array(
	'label' => $this->Language('Content name'),
	'class' => 'bootstrap-form-control',
	'required' => 'true',
	'required-label' => $this->Language( 'Content name required' ),
	'autocomplete' => 'off'
));

$form->addElem('hidden', 'base64_content', array(
	'' => ''
))->after('menu');

$form->addElem('hidden', 'fullhtml', array(
	'' => ''
))->after('menu');

$form->addElem('hidden', 'addContent', array(
	'value' => 'addContent'
))->after('menu');

$form->addElem('submit', 'addContent', array(
	'value' => $this->Language( 'Add content' ),
	'class' => 'bootstrap-submit-primary',
	'style' => 'width:100%;'
));

$form->toString();

?>
</div>