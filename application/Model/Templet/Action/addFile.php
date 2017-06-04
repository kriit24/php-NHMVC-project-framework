<?
namespace Model\Templet\Action;

abstract class addFile{

	public static function init(){

		if( !preg_match('/\.js/i', $_POST['new_file']) && !preg_match('/\.css/i', $_POST['new_file']) ){

			new \Library\Component\Error('File must be js or css', '', true);
			return;
		}

		$dirLoc = _DIR .'/' . _APPLICATION_PATH .'/Template/'. \Conf\Conf::_TEMPLATE .'/public';

		if( preg_match('/\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)/i', $_POST['new_file']) || preg_match('/\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)/i', $_POST['new_file']) ){

			list($new_from, ) = explode('?', $_POST['new_file']);
			$filename = preg_replace('/([a-z_]+)\:\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\//s', '', $new_from);
			$filename = preg_replace('/([a-z_]+)\:\/\/([a-zA-Z0-9\__]+)\.([a-zA-Z0-9\__]+)\//s', '', $filename);

			$getfrom = $new_from;
			if( substr($getfrom, 0, 4) != 'http' )
				$getfrom = 'http:' . $getfrom;

			$filename = (preg_match('/\.js/i', $filename) ? 'js/' : 'css/') . $filename;

			$dirDest = $dirLoc . '/' . dirname($filename);
			if( !is_dir($dirDest) )
				mkdir($dirDest, 0755, true);

			$content = file_get_Contents($getfrom);
			file_put_contents($dirDest . '/' . basename($getfrom), $content);
		}
		else{

			$dirLoc = $dirLoc . (preg_match('/\.js/i', $_POST['new_file']) ? '/js' : '/css');

			file_put_contents($dirLoc . '/' . $_POST['new_file'], $content);
		}
	}
}

?>