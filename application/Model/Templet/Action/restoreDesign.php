<?
namespace Model\Templet\Action;

abstract class restoreDesign{

	public static function init(){

		$dir = _DIR .'/' . _APPLICATION_PATH .'/Template/'. \Conf\Conf::_TEMPLATE .'/public';
		\Library\FileSystem::singleton()->unlinkRec( $dir );
		\Library\FileSystem::singleton()->fileCopyRec( \Model\Templet\Controller::ORIGIN_BACKUP_FOLDER, '', $dir );

		die(\Library\Http::redirect('?action=updatedesign'));
	}
}

?>