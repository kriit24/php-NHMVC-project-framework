<?
namespace Library;

class FileSystem{

	use \Library\Component\Singleton;

	const PARENT_DIR = array('.', '..');
	const IS_FILE = 'is_file';
	const IS_DIR = 'is_dir';

	//SAN DIR FOR FILES OR DIRECTORYS
	private function scanSystem( $path, $filterMask = '*', $baseName = false, $returnType = '' ){

		$dataRows = array();
		if( !is_dir($path) )
			die( 'Directory not found: '.$path );

		if( $returnType == self::IS_FILE )
			$tmpData = glob( $path.'/'.$filterMask, GLOB_BRACE );
		if( $returnType == self::IS_DIR )
			$tmpData = glob( $path.'/'.$filterMask, GLOB_BRACE|GLOB_ONLYDIR );

		foreach($tmpData as $fileOrDirectory){

			$dataRows[] = ($baseName ? basename($fileOrDirectory) : $fileOrDirectory);
		}
		return $dataRows;
	}

	//SEARCH FILES
	//{name1,name2}*.jpg
	//!name,!name2
	//$baseName - return basename
	function scanfile($path, $fileMask = '', $baseName = false){

		$path = $this->dir( $path );

		if( !$fileMask )
			$fileMask = '{}*';

		if( substr($fileMask, 0, 1) == '!' )
			return array_values( preg_grep( '/^((?!'.str_replace(array(',', '!'), array('|', ''), $fileMask).').)*$/', $this->scanSystem($path, $fileMask, $baseName, self::IS_FILE) ));
		else
			return $this->scanSystem($path, $fileMask, $baseName, self::IS_FILE);
	}

	function inc($file){

		if( gettype($file) != 'string' )
			die('Inc value must be string');

		$list = Component\Register::getRegister('INCLUDES');
		if( !in_array($file, $list) )
			Component\Register::setRegister('INCLUDES', (gettype($file) == 'string' ? array($file) : $file));
	}

	function dir( $directory ){

		return str_replace('/', DIRECTORY_SEPARATOR, $directory);
	}

	//SEARCH DIRECTORYS
	//{name1,name2}*
	//!name,!name2
	//$baseName - return basename
	function scandir( $path, $recursive = false, $dirMask = '', $baseName = false ){

		$path = $this->dir( $path );
		if( $recursive )
			return $this->scandirRecursive( $path );

		if( !$dirMask )
			$dirMask = '{}*';

		if( substr($dirMask, 0, 1) == '!' )
			return array_values( preg_grep( '/^((?!'.str_replace(array(',', '!'), array('|', ''), $dirMask).').)*$/', $this->scanSystem($path, $dirMask, $baseName, self::IS_DIR) ));
		else
			return $this->scanSystem($path, $dirMask, $baseName, self::IS_DIR);
	}
	
	private function scandirRecursive( $path ){

		$dataRows = array();
		$path = $this->dir( $path );
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

	function mkdir( $path ){

		$path = $this->dir( $path );
		if( is_file($path) )
			die('Cannot make dir: <b>'.$path.'</b> file with same name allready exists ');
		if( !is_dir($path) )
			mkdir($path, 0775, true);
		return $path;
	}

	function fileToLines( $file ){

		if( file_exists($file) )
			return file($file);
		return array();
	}

	public function file_put($fileName, $content){

		try{

			$this->mkdir( dirname($fileName) );
			file_put_contents($fileName, $content);
		}
		catch(\Exception $e){

			return false;
		}
		return true;
	}

	function uploadFile($fileName, $allowUpload = array(), $dir = ''){

		if($_FILES[$fileName] && $_FILES[$fileName]['size'] > 0){

			if( !$dir )
				$dir = _DIR.'/tmp/upload';
			$this->mkdir($dir);
			$tmpFile = $_FILES[$fileName];

			$ext = pathinfo($tmpFile['name'], PATHINFO_EXTENSION);
			if( !in_array($ext, $allowUpload) ){

				$this->error = 'Not allowed filetype';
				return false;
			}
			move_uploaded_file($tmpFile['tmp_name'], $dir.'/'.$tmpFile['name'] );
			return $dir.'/'.$tmpFile['name'];
		}
	}

	function fileExtension($fileName){

		return pathinfo($fileName, PATHINFO_EXTENSION);
	}

	function getFileCType($fileName){

		switch( $this->fileExtension($fileName) ) {

			case "pdf": $ctype="application/pdf"; break;
			case "exe": $ctype="application/octet-stream"; break;
			case "zip": $ctype="application/zip"; break;
			case "gzip": $ctype="application/gzip"; break;
			case "doc": $ctype="application/msword"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			case "mp3": $ctype="audio/mpeg"; break;
			case "wav": $ctype="audio/x-wav"; break;
			case "mpeg":
			case "mpg":
			case "mpe": $ctype="video/mpeg"; break;
			case "mov": $ctype="video/quicktime"; break;
			case "avi": $ctype="video/x-msvideo"; break;

			//The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
			case "php":
			case "htm":
			case "html":
			case "txt": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;

			default: $ctype="application/force-download";
		}
		return $ctype;
	}

	/**
	* download file from another domain
	* @param String $file AS full url based file http://domain/image.png
	*/
	function downloadFile($file){

		//First, see if the file exists
		if (!is_file($file)) {

			$this->error = '404 File not found! '.$file;
			return false;
		}

		//Gather relevent info about file
		$len = filesize($file);
		$filename = basename($file);

		//Begin writing headers
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");

		//Use the switch-generated Content-Type
		header("Content-Type: ".$this->getFileCType($filename));

		//Force the download
		$header="Content-Disposition: attachment; filename=".$filename.";";
		header($header );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".$len);
		@readfile($file);
	}
}

?>