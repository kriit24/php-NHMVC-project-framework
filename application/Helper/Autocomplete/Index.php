<?
namespace Helper\Autocomplete;

class Index extends \Library{

	protected function Index(){

		if( $_GET['action'] )
			echo json_encode($this->GET( 'action' )->action());
		else
			echo json_encode(array());
	}
}


?>