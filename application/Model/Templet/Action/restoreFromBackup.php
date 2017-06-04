<?
namespace Model\Templet\Action;

abstract class restoreFromBackup{

	public static function init(){

		$dir = _DIR .'/' . _APPLICATION_PATH .'/Template/'. \Conf\Conf::_TEMPLATE .'/public';
		\Library\FileSystem::singleton()->unlinkRec( $dir );
		\Library\FileSystem::singleton()->fileCopyRec( \Model\Templet\Controller::BACKUP_FOLDER . '/' . $_GET['directory'], '', $dir );

		die(\Library\Http::redirect( \Library::singleton()->url( array('route' => 'Model', 'model' => 'Templet') ) ));
	}
}

?>