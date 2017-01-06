<?
namespace Library;

class Ftp extends Component\isPrivate{

	use \Library\Component\Singleton;

	function connect($server, $user, $password, $passiveMode = true){

		$this->conn_id = ftp_connect($server);
		$login_result = ftp_login($this->conn_id, $user, $password);
		ftp_pasv($this->conn_id, $passiveMode);
	}

	function create_folder($folder){

		@ftp_mkdir($this->conn_id, $folder);
	}

	function upload($file, $file_name){

		ftp_put($this->conn_id, $file_name, $file, FTP_ASCII);
	}

	function upload_dir($dir, $uploadTo){

		$file = new FileSystem;

		$files = $file->scandir( $dir, true );

		foreach($this->File->FilesRecursive as $v){

			ftp_put($this->conn_id, $uploadTo.'/'.basename($v), $v, FTP_ASCII);
		}
	}

	function close(){

		ftp_close($this->conn_id);
	}
}

?>