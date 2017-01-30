<?
namespace Library\Component;

class FileSystem{

	const PARENT_DIR = array('.', '..');
	const IS_FILE = 'is_file';
	const IS_DIR = 'is_dir';

	//SAN DIR FOR FILES OR DIRECTORYS
	private function scanSystem( $path, $filterMask = '*', $baseName = false, $returnType = '' ){

		$dataRows = array();
		if( !is_dir($path) )
			die( 'Directory not found: '.$path );

		if( $returnType == self::IS_FILE )
			$tmpData = glob( $path . (substr($path, -1) == '/' ? '' : '/') . $filterMask, GLOB_BRACE );
		if( $returnType == self::IS_DIR )
			$tmpData = glob( $path . (substr($path, -1) == '/' ? '' : '/') . $filterMask, GLOB_BRACE|GLOB_ONLYDIR );

		foreach($tmpData as $fileOrDirectory){

			if( $returnType == self::IS_FILE && is_dir($fileOrDirectory) )
				continue;

			$dataRows[] = ($baseName ? basename($fileOrDirectory) : $fileOrDirectory);
		}
		return $dataRows;
	}

	private function scanFileRecursive( $path, $fileMask, $baseName ){

		$dataRows = $this->scanSystem( $path, $fileMask, $baseName, self::IS_FILE );
		$tmpData = $this->scanSystem( $path, '{}*', false, self::IS_DIR );
		if( !empty($tmpData) ){

			foreach($tmpData as $newPath){

				$dataRows = self::scanFileRecursive( $newPath, $fileMask, $baseName );
			}
		}
		return $dataRows;
	}

	private function scandirRecursive( $path ){

		$dataRows = array();
		$tmpData = scandir( $path );
		foreach($tmpData as $fileOrDirectory){

			if( !in_array($fileOrDirectory, self::PARENT_DIR) ){

				$dataRows[] = $fileOrDirectory;

				if( is_dir($path.'/'.$fileOrDirectory) ){

					foreach(self::scandirRecursive( $path.'/'.$fileOrDirectory ) as $list){

						$dataRows[] = $fileOrDirectory.'/'.$list;
					}
				}
			}
		}
		return $dataRows;
	}

	public function __call( $method, $args ){

		return call_user_func_array( array($this, $method), $args );
	}
}
?>