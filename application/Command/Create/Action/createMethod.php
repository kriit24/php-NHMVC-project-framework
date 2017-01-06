<?
namespace Command\Create\Action;

abstract class createMethod{

	public static function init(){

		$fileSystem = new \Library\FileSystem;
		$scandir = dirname(__DIR__).'/inc/template/Method';

		if( is_dir($scandir) ){

			$methodIsSet = false;

			foreach($fileSystem->scandir($scandir, true) as $v){

				$file = $fileSystem->dir($scandir.'/'.$v);

				if( is_file($file) ){

					$array = array();

					$array['folder'] = $fileSystem->dir( dirname(__DIR__, 3).'/'.$_POST['folder'].'/'.$_POST['model_name'] . ( dirname($v) != '.' ? '/'.str_replace($scandir, '', dirname($v)) : '') );
					$array['name'] = basename($_POST['name']);
					$array['uname'] = ucfirst(basename($_POST['name']));
					$array['method'] = basename($_POST['name']);
					$array['file'] = basename(str_replace($scandir.'/', '', $v));
					$array['file'] = replace::init($array['file'], $array);
					$array['content'] = $fileSystem->fileToLines( $array['folder'] . '/' . $array['file'] );
					$lineIsSet = false;

					if( $fileSystem->dir($array['folder'] .'/'. $array['file']) ){

						$content = file_Get_contents($file);
						$content = replace::init($content, $array);

						if( is_Array($array['content']) ){

							foreach($array['content'] as $k => $v){

								if( 
									(
										preg_match('/(function([\s_]+)'.$array['method'].'\()|function([\s_]+)'.$array['method'].'\s+\(/i', strtolower($v))
										|| preg_match('/(function([\s_]+)get'.$array['uname'].'Data\()|function([\s_]+)get'.$array['uname'].'Data\s+\(/i', strtolower($v))
									)
									&& $lineIsSet == false
								){

									$lineIsSet = true;
									$methodIsSet = true;
								}

								if( 
									preg_match('/function\s+([a-zA-Z0-9\_\s_]+)\(/i', $v) 
									&& !preg_match('/(function([\s\__]+)construct\()|function([\s\__]+)construct\s+\(/i', strtolower($v))
									&& !preg_match('/(function([\s_]+)'.$array['method'].'\()|function([\s_]+)'.$array['method'].'\s+\(/i', strtolower($v))
									&& $lineIsSet == false
								){

									$line = $content;
									$line .= "\n\n";
									$line .= $v;

									$array['content'][$k] = $line;
									$lineIsSet = true;
								}
							}
						}
						else{

							$array['content'][] = $content;
						}
						file_put_contents($array['folder'].'/'.$array['file'], implode("", $array['content']));
					}
				}
			}
			if( !$methodIsSet ){

				$abstractLines = $fileSystem->fileToLines( $fileSystem->dir( dirname(__DIR__, 3).'/'.$_POST['folder'].'/'.$_POST['model_name'] . '/_Abstract.php' ) );
				$registerLineFound = false;
				$privilegesLineFound = false;
				foreach($abstractLines as $k => $v){

					if( preg_match('/(function([\s_]+)register\()|function([\s_]+)register\s+\(/i', strtolower($v)) ){

						$registerLineFound = true;
					}
					if( preg_match('/(function([\s_]+)privileges\()|function([\s_]+)privileges\s+\(/i', strtolower($v)) ){

						$privilegesLineFound = true;
					}


					if( preg_match('/return\s+array\(/i', strtolower($v)) && $registerLineFound ){

						$abstractLines[$k] .= "\t\t\t//'".$_POST['name']."' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => true),\n";
						$registerLineFound = false;
					}

					if( preg_match('/return\s+array\(/i', strtolower($v)) && $privilegesLineFound ){

						$abstractLines[$k] .= "\t\t\t//'".$_POST['name']."' => array('SUPERADMIN'),\n";
						$privilegesLineFound = false;
					}
				}

				file_put_contents( $fileSystem->dir( dirname(__DIR__, 3).'/'.$_POST['folder'].'/'.$_POST['model_name'] . '/_Abstract.php'), implode("", $abstractLines) );
				return true;
			}
			return false;
		}
		return false;
	}
}
?>