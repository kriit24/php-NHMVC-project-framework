<?
namespace Library;

class FileSystem extends Component\FileSystem{

	use \Library\Component\Singleton;

	//SEARCH FILES
	//{name1,name2}*.jpg
	//!name,!name2
	//$baseName - return basename
	function scanfile($path, $recursive = false, $fileMask = '', $baseName = false){

		$path = $this->dir( $path );
		if( !$fileMask )
			$fileMask = '{}*';

		if( $recursive )
			return $this->scanFileRecursive( $path, $fileMask, $baseName );

		if( substr($fileMask, 0, 1) == '!' )
			return array_values( preg_grep( '/^((?!'.str_replace(array(',', '!'), array('|', ''), $fileMask).').)*$/', $this->scanSystem($path, $fileMask, $baseName, Component\FileSystem::IS_FILE) ));
		else
			return $this->scanSystem($path, $fileMask, $baseName, Component\FileSystem::IS_FILE);
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
			return array_values( preg_grep( '/^((?!'.str_replace(array(',', '!'), array('|', ''), $dirMask).').)*$/', $this->scanSystem($path, $dirMask, $baseName, Component\FileSystem::IS_DIR) ));
		else
			return $this->scanSystem($path, $dirMask, $baseName, Component\FileSystem::IS_DIR);
	}
	
	function mkdir( $path ){

		$path = $this->dir( $path );
		if( is_file($path) )
			die('Cannot make dir: <b>'.$path.'</b> file with same name allready exists ');
		if( !is_dir($path) )
			mkdir($path, 0755, true);
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

	public function fileCopyRec( $path, $fileMask, $dest, $origPath = null ){

		if( !$origPath )
			$origPath = $path;

		if( !$fileMask )
			$fileMask = '*';

		$files = $this->scanSystem( $path, $fileMask, false, Component\FileSystem::IS_FILE );
		$files = array_merge(
			$files, 
			$this->scanSystem( $path, '.*', false, Component\FileSystem::IS_FILE )
		);
		foreach($files as $file){

			$fileDest = $dest . (substr($dest, -1) == '/' ? '' : '/') . str_replace($origPath, '', $file);
			if( !is_dir( dirname($fileDest) ) )
				mkdir(dirname($fileDest), 0755, true);

			copy($file, $fileDest);
		}

		$tmpData = $this->scanSystem( $path, '{}*', false, Component\FileSystem::IS_DIR );
		if( !empty($tmpData) ){

			foreach($tmpData as $newPath){

				self::fileCopyRec( $newPath, $fileMask, $dest, $origPath );
			}
		}
	}

	public function unlinkRec( $path ){

		if( !is_dir($path) )
			return;

		$files = $this->scanSystem( $path, '*', false, Component\FileSystem::IS_FILE );
		$files = array_merge(
			$files, 
			$this->scanSystem( $path, '.*', false, Component\FileSystem::IS_FILE )
		);
		foreach($files as $file){

			unlink($file);
		}
		$tmpData = $this->scanSystem( $path, '{}*', false, Component\FileSystem::IS_DIR );
		if( !empty($tmpData) ){

			foreach($tmpData as $newPath){

				self::unlinkRec( $newPath );
				if( is_dir($newPath) )
					rmdir( $newPath );
			}
		}
		rmdir( $path );
	}

	function uploadFile($uploadName, $allowUpload = array(), $dir = '', $fileName = ''){

		if($_FILES[$uploadName] && $_FILES[$uploadName]['size'] > 0){

			if( !$dir )
				$dir = _DIR.'/tmp/upload';
			$this->mkdir($dir);
			$tmpFile = $_FILES[$uploadName];

			$ext = pathinfo($tmpFile['name'], PATHINFO_EXTENSION);
			$fileNameExt = pathinfo($fileName, PATHINFO_EXTENSION);
			if( !empty($allowUpload) && !in_array($ext, $allowUpload) ){

				new \Library\Component\Error('Not allowed filetype', '', true);
				return false;
			}
			$dir = (substr($dir, -1) == '/' ? substr($dir, 0, -1) : $dir);
			$fileName = $fileName ? $fileName . '.' . ($fileNameExt ? $fileNameExt : $ext) : $tmpFile['name'];
			move_uploaded_file($tmpFile['tmp_name'], $dir .'/'. $fileName );
			return $dir.'/'.$fileName;
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
	function downloadFile($file, $fileName = ''){

		//First, see if the file exists
		if (!is_file($file)) {

			new \Library\Component\Error('404 File not found! '.$file, '', true);
			return false;
		}

		//Gather relevent info about file
		$len = filesize($file);
		if( !$fileName )
			$fileName = basename($file);

		//Begin writing headers
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");

		//Use the switch-generated Content-Type
		header("Content-Type: ".$this->getFileCType($fileName));

		//Force the download
		$header="Content-Disposition: attachment; filename=".$fileName.";";
		header($header );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".$len);
		@readfile($file);
	}
}

?>