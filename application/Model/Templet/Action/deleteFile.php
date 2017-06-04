<?
namespace Model\Templet\Action;

abstract class deleteFile{

	public static function init(){
		
		$dirLoc = _DIR .'/' . _APPLICATION_PATH .'/Template/'. \Conf\Conf::_TEMPLATE .'/public';

		unlink( $dirLoc . '/' . urldecode($_GET['file']) );

		die(\Library\Http::redirect( \Library\Url::singleton()->url( array('model' => 'Templet', 'action' => 'detail') ) ));
	}
}

?>