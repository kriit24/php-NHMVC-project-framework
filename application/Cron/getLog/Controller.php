<?
namespace Cron\getLog;

class Controller extends \Library{

	private $logDir;
	protected $subDir;

	public function getLog(){

		$class = new \ReflectionClass("\Cron\JobLoader");
		$property = $class->getProperty("logDir");
		$property->setAccessible(true);

		$obj = new \Cron\JobLoader;
		$this->logDir = $property->getValue($obj); // Works
		$this->subDir = $this->scandir($this->logDir);
		$this->getSubFolder();

		return $this;
	}

	public function getSubFolder(){

		if( $_GET['subdir'] && is_dir($this->logDir . '/' .$_GET['subdir']) ){

			$this->subFile = $this->scanfile($this->logDir . '/' .$_GET['subdir']);
			$this->getSubFile();
		}
	}

	public function getSubFile(){

		if( $_GET['subfile'] && is_file($this->logDir . '/' .$_GET['subdir'] . '/' . $_GET['subfile']) ){

			$this->subFileContent = file_get_contents($this->logDir . '/' .$_GET['subdir'] . '/' . $_GET['subfile']);
		}
	}
}

?>