<?
namespace Library\Component;

class log{

	use Singleton;

	private $path = _DIR . '/tmp/logs/logs';
	private $logName = '';
	private $historyLength = 0;

	/*
	$parentName like class name or project name
	$name like log name
	$historyLength in days
	*/

	public function __construct($parentName = '', $name = '', $historyLength = 0){

		if( !$parentName )
			return;

		$this->logName = $parentName . '-' . $name;
		$this->historyLength = $historyLength;

		$path = $this->path . '/' . $this->logName . '/' . date('d.m.Y') . '/' . date('H');
		\Library\FileSystem::singleton()->mkdir( $path );

		$this->registerlog();
		$this->path = $path;
	}

	public function logData($data){

		if( !$this->logName )
			die('Log name not set');

		if( is_array($data) || is_object($data) )
			$data = json_encode($data);

		if( is_file($this->path . '/' . $this->logName) )
			$data .= "\n" . file_get_contents($this->path . '/' . $this->logName);
		file_put_contents($this->path . '/' . $this->logName, $data);
	}

	public function clearHistory(){

		if( is_dir($this->path) ){

			foreach( \Library\Filesystem::singleton()->scanfile( $this->path ) as $file ){

				$historyLength = file_Get_contents($file);
				if( is_numeric($historyLength) ){

					$dirName = str_replace('.file', '', basename($file));
					if( is_dir( $this->path . '/' . $dirName ) ){

						foreach( \Library\Filesystem::singleton()->scandir( $this->path . '/' . $dirName ) as $dir ){

							$date = basename($dir);
							if( strtotime($date) < strtotime(date('d.m.Y').' -'.$historyLength.' day') ){

								system('rm -r ' . $dir);
							}
						}
					}
				}
			}
		}
	}

	private function registerlog(){

		$logFile = $this->logName . '.file';

		if( is_file($this->path . '/' . $logFile) ){

			if( !$historyLength = \Library\Session::log()->{$logFile} ){

				$historyLength = file_get_contents($this->path . '/' . $logFile);
				\Library\Session::log($logFile, $historyLength);
			}

			if( $this->historyLength != $historyLength )
				file_put_contents($this->path . '/' . $logFile, $this->historyLength);
		}
		else{

			file_put_contents($this->path . '/' . $logFile, $this->historyLength);
		}
	}
}

?>