<?
\Model\Dashboard\Index::singleton()->title(
	$this->Language('Build')
);

$this->Form->buildForm();

if( $_POST['model_name'] )
	$this->script('getModelNames( $("select[name=\"create\"]"), "'.$_POST['model_name'].'" );');

?>

<script type="text/javascript">
var selected_columns = '<?=(json_encode( (\Session::tableColumns() ? \Session::tableColumns(true) : array()) ));?>';
</script>