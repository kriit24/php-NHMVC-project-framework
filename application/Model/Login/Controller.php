<?
namespace Model\Login;

class Controller extends \Library{

	public function getView(){

		if( \Session::userData()->logged == true )
			return 'Logged';
		return 'Login';
	}
}

?>