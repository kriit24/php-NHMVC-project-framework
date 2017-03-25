<?
namespace {namespace};

abstract class update{uname}{

	public static function init(){

		$data = $_POST;

		pre($data);
		pre($_GET);
		//\Table\{table}::singleton()->Update($data, array('id' => $_GET['id']));
	}
}
?>