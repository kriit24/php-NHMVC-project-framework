<?
namespace Command\Patch;

class Controller extends \Library{

	const PATCH_DIR = _DIR .'/database';

	public function PatchDb(){

		if ($handle = opendir(self::PATCH_DIR)) {

			if( is_file(_DIR . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'patch.sql') )
				$lastPatch = filemtime(_DIR . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'patch.sql');
			else
				$lastPatch = strtotime('01.01.1970');

			$sql = new \Library\Sql();

			while (false !== ($file = readdir($handle))) {

				if( preg_match('/\.sql/i', $file) ){

					if( filemtime(self::PATCH_DIR . DIRECTORY_SEPARATOR . $file) > $lastPatch ){

						echo "\e[31m" . self::PATCH_DIR . DIRECTORY_SEPARATOR . $file . "\e[0m\n";

						$fcontent = file_get_contents(self::PATCH_DIR . DIRECTORY_SEPARATOR . $file);
						foreach( explode(';', $fcontent) as $line ){

							$line = trim($line);

							if( !empty($line) ){

								echo "\n\n" . $line . "\n";
								$sql->error( 2 );
								$sql->Query( $line );

								if( !empty($err = $sql->getError()) ){

									echo "\e[31m";
									pre($err);
									echo "\e[0m\n";
								}
								else{

									echo "\e[32mDONE\e[0m\n";
								}
							}
						}

						echo "\n";
					}
				}
			}

			file_put_contents(_DIR . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'patch.sql', 'true');

			closedir($handle);
		}
	}
}
?>