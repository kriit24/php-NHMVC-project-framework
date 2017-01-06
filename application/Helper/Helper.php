<?
namespace Helper;

class Helper extends \Library\Structur\Loader{

	public function __construct(){

		if( $_GET['route'] == 'Helper' && in_array($_GET['helper'], array('Autocomplete')) )
			parent::__construct();
	}
}

?>