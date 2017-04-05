<?
namespace Cron;

class Job extends JobLoader{

	public function __construct(){

		if( strtolower($_GET['route']) == 'cron' ){

			if( _SHELL || \Session::userData()->type == 'SUPERADMIN' ){

				parent::__construct();
				if( $_GET['cron'] )
					$this->getJob($_GET['cron']);
				else
					$this->getJobs();
				self::destruct();
			}
			else
				die(header('Location:'._URI));
		}
	}

	private function getJobs(){

		$jobs = $this->scandir( __DIR__, false, '', true );
		foreach($jobs as $file){

			$className = $file;
			$this->getJob($className);
		}
	}

	private function getJob($jobName){
		
		$jobClass = '\\'.__NAMESPACE__.'\\'.$jobName.'\\Index';
		try{

			//if some php error interrupts cronjob then dont execute that cron next time
			if( $this->loadClass( $jobName, true ) ){

				if( class_exists($jobClass) ){

					$this->loadClass( $jobName, false );

					$time = $jobClass::CRONTIME;
					$escape = $jobClass::ESCAPE;
					$loadCron = $this->cronTime($time);

					$this->logJob($jobName, $time, $loadCron);
					if( $loadCron == true )
						$this->loadJob($jobClass, $jobName, $escape);
				}
				else
					new \Library\Component\Error('Job class not found '.$jobClass, '', false, true, true);
			}
		}
		catch(\Exception $e){
		}
	}

	private function loadClass( $jobName, $load ){

		$file = $this->logDir.'/'.$jobName.'.error';

		if( $load ){

			if( is_file($file) ){

				new \Library\Component\Error('Cronjob PHP ERROR @ '.$jobName.'', '', false, true, true);
				return false;
			}

			file_put_contents($file, 'true');
		}
		else{

			unlink($file);
		}
		return true;
	}

	private static function destruct(){

		exit;
	}
}

?>