<?
namespace Command\Create\Action;

abstract class createModel{

	public static function init( $folder ){

		$fileSystem = new \Library\FileSystem;
		$success = false;

		if( is_dir(dirname(__DIR__).'/inc/template/'.$folder) )
			$scandir = dirname(__DIR__).'/inc/template/'.$folder;
		else
			$scandir = dirname(__DIR__).'/inc/template/'.\Command\Create\Form::TEMPLATE;

		if( is_dir($scandir) ){

			foreach($fileSystem->scandir($scandir, true) as $v){

				$file = $fileSystem->dir($scandir.'/'.$v);

				if( is_file($file) ){

					$array = array();

					if( preg_match('/parent/i', $v) ){

						$v = str_replace('parent', '', $v);
						$array['folder'] = $fileSystem->mkdir( dirname(__DIR__, 3).'/'.$_POST['folder'] . ( dirname($v) != '.' ? '/'.str_replace($scandir, '', dirname($v)) : '') );
					}
					else
						$array['folder'] = $fileSystem->mkdir( dirname(__DIR__, 3).'/'.$_POST['folder'].'/'.$_POST['name'] . ( dirname($v) != '.' ? '/'.str_replace($scandir, '', dirname($v)) : '') );
					$array['namespace'] = str_replace('/', '\\', str_replace(dirname(__DIR__, 3).'/', '', $array['folder']));
					$array['name'] = basename($_POST['name']);
					$array['uname'] = ucfirst(basename($_POST['name']));
					$array['class'] = ucfirst(basename($_POST['name']));
					$array['method'] = ucfirst(basename($_POST['name']));
					$array['file'] = basename(str_replace($scandir.'/', '', $v));
					$array['file'] = replace::init($array['file'], $array);

					if( !is_file( $array['folder'] .'/'. $array['file']) ){

						$success = true;
						$content = file_get_contents($file);
						if( !in_Array(basename($v), array('script.js', 'style.css')) )
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