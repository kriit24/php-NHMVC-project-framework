<?
namespace Command\Create\Action;

abstract class createAction{

	public static function init(){

		$fileSystem = new \Library\FileSystem;
		$success = false;

		$scandir = dirname(__DIR__).'/inc/template/Action';
		if( is_dir($scandir) ){

			foreach($fileSystem->scandir($scandir, true) as $v){

				$file = $fileSystem->dir($scandir.'/'.$v);

				if( is_file($file) ){

					$array = array();

					$array['folder'] = $fileSystem->mkdir( dirname(__DIR__, 3).'/'.$_POST['folder'].'/'.$_POST['model_name'] . '/Action' );
					$array['namespace'] = str_replace('/', '\\', str_replace(dirname(__DIR__, 3).'/', '', $array['folder']));
					$array['name'] = basename($_POST['name']);
					$array['uname'] = ucfirst(basename($_POST['name']));
					$array['class'] = ucfirst(basename($_POST['name']));
					$array['method'] = ucfirst(basename($_POST['name']));
					$array['file'] = basename(str_replace($scandir.'/', '', $v));
					$array['file'] = replace::init($array['file'], $array);

					if( !is_file( $array['folder'] .'/'. $array['file'] ) ){

						$success = true;
						$content = file_get_contents($file);
						$content = replace::init($content, $array);
						file_put_Contents( $array['folder'] .'/'. $array['file'], $content );
					}
				}
			}
		}
		return $success;
	}
}
?>