<?
namespace Library;

class GZip{

	use \Library\Component\Singleton;

	const GZLocation = _DIR.'/tmp/gzip';

	private $GZipType = '';
	private $fileSystem;

	function __construct(){

		$this->fileSystem = new FileSystem;
		$this->fileSystem->mkdir( self::GZLocation );

		if (preg_match_all ("/(gzip|deflate)/i", $_SERVER['HTTP_ACCEPT_ENCODING'], $matches)){

			$this->GZipType = ucfirst($matches[0][0]);
		}
	}

	private function _GZGzip($data){

		$_packed_data	= gzcompress($data, 5);
		return "\x1f\x8b\x08\x00\x00\x00\x00\x00".
				  substr($_packed_data, 0, strlen($_packed_data) - 4).
				  pack('V', crc32($data)).
				  pack('V', strlen($data));
	}

	private function _GZDeflate($data){

		return gzdeflate ($data, 5);
	}

	private function _GZOpen($fileName){

		if( $this->fileSystem->fileExtension($fileName) == 'gzip' )
			return file_get_contents($fileName);
		return file_get_contents($fileName. '.gzip');
	}

	private function _GZShowFile($fileName, $return){

		if( $return )
			return $this->_GZOpen($fileName);

		$fName = str_replace('.gzip', '', $fileName);

		header ("Content-Encoding: ".strtolower($this->GZipType));
		header('Content-Type: '.$this->fileSystem->getFileCType($fName));

		echo $this->_GZOpen($fileName);
	}

	private function _GZOBContent($data){

		return $this->{'_GZ'.$this->GZipType}($data);
	}

	private function _GZShowContent(){

		header ("Content-Encoding: ".strtolower($this->GZipType));

		ob_implicit_flush (0);
		ob_start (array($this, '_GZOBContent'));
	}

	private function _GZWrite($data = '', $fileName = ''){

		$_data = '';
		if( $data )
			$_data = $data;
		if( $fileName )
			$_data = file_get_contents($fileName);

		if( $_data ){

			$gzipData = $this->{'_GZ'.$this->GZipType}($_data);
			$fName = ($fileName ? $fileName : tempnam(self::GZLocation)) . '.gzip';
			file_put_contents($fName, $gzipData);
			return $fName;
		}
		return '';
	}

	function GZWriteFile($fileName){

		return $this->_GZWrite('', $fileName);
	}

	function GZWriteContent($content){

		return $this->_GZWrite($content, '');
	}

	function GZGet($fileName = '', $return = false){

		if( $fileName )
			return $this->_GZShowFile($fileName, $return);
		else if( !$fileName && $return )
			die('OB cannot be return');
		else
			return $this->_GZShowContent();
	}
}
?>