<?
namespace Model\Page;

class Controller extends \Library{

	public function getView(){

		$path = 'Pages/' . _LANG;
		if( !is_dir(__DIR__ .'/view/'. $path) )
			$path = 'Pages/' . _DLANG;

		if( $_GET['view'] )
			return $path .'/'. $_GET['view'];
		return $path .'/Index';
	}
}

?>