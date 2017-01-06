<?
namespace Library;

/**
* generates zip
* 
*/
class Zip extends Component\isPrivate{

	/**
	* generates zip class
	*/
	function __construct(){

		if(class_exists('ZipArchive'))
			$this->zip = new ZipArchive;
	}

	/**
	* open zip file
	* @param String $file AS filename
	*/
	function open($file){

		if(!file_exists($file))
			file_put_contents($file, '');

		return $this->zip->open($file);
	}

	/**
	* unlink zip file
	* @param String $zip_file AS filename
	*/
	function unlink($zip_file){

		if(file_exists($zip_file))
			unlink($zip_file);
	}

	/**
	* extract zip file
	* @param String $file AS filename
	* @param String $location AS destination to extract
	*/
	function extract($file, $location){

		if($this->open($file) == true){

			$this->zip->extractTo($location);
		}
		$this->close();
	}

	/**
	* att file to arhive
	* @param String $file_name AS file name in zip file
	* @param String $local_name AS filename what add
	* @param String $zip_file AS zip filename
	*/
	function addFile($file_name, $local_name, $zip_file){

		if($this->open($zip_file) == true){

			$this->zip->addFile($file_name, $local_name);
		}
	}

	/**
	* close zip file
	*/
	function close(){

		$this->zip->close();
	}
}
?>