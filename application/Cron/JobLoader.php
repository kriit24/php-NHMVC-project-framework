<?
namespace Cron;

class JobLoader extends \Library{

	private $toDay;
	protected $logDir = _DIR.'/tmp/logs/cronjob';

	const DEBUG = (_DEBUG == 'cron' ? true : false);

	public function __construct(){

		$this->toDay = date('d.m.Y');
		$this->mkdir($this->logDir.'/'.$this->toDay);
	}

	private function clearCache( $jobName ){

		if( is_file($this->logDir.'/'.$jobName) ){

			$jobStartTime = filemtime($this->logDir.'/'.$jobName);
			if( strtotime('+1 hour', $jobStartTime) < strtotime('now') ){

				unlink($this->logDir.'/'.$jobName);

				$this->toDayDir( $jobName );

				$end = is_file($this->logDir.'/'.$this->toDay.'/'.$jobName) ? file_get_contents($this->logDir.'/'.$this->toDay.'/'.$jobName) : '';
				$end = str_replace('{end}', ' DECLINED '.date("H:i:s"), $end);
				file_put_contents($this->logDir.'/'.$this->toDay.'/'.$jobName, $end);

				new \Library\Component\Error('Cronjob loadtime exceeded. Jobname: '.$jobName, '', false, true, true);
			}
		}
	}

	private function toDayDir( $jobName ){

		parent::mkdir($this->logDir.'/'.$this->toDay);
	}

	private function isReady( $jobName, $escape ){

		if( self::DEBUG || $escape == true )
			return true;

		if( !is_file($this->logDir.'/'.$jobName) ){

			file_put_contents($this->logDir.'/'.$jobName, 'loading');

			$this->toDayDir( $jobName );
			file_put_contents($this->logDir.'/'.$this->toDay.'/'.$jobName, 'START '.date("H:i:s")." {end}\n", FILE_APPEND);
			return true;
		}
		return false;
	}

	public function cronTime( $time ){

		$time = explode(' ', $time);
		foreach(array(date('i'), date('H'), date('d'), date('m'), date('W')) as $k => $v){

			$exp = array_flip(explode(',', $time[$k]));
			if( $time[$k] == '*' || isset($exp[$v]) )
				$load .= true;
		}
		return ($load == 11111 ? true : false);
	}

	public function loadJob( $jobClassName, $jobName, $escape ){
		
		$this->clearCache( $jobName );
		if( $this->isReady( $jobName, $escape ) ){

			try{

				$jobClass = new $jobClassName;
			} catch (Exception $e) {

				file_put_contents(_DIR.'/tmp/cronjob/errors', 'job: '.$jobName.' datetime: '.date('d.m.Y H:i:s').' error: '.$e->getMessage()."\n", FILE_APPEND);
			}
			$this->endCron( $jobName );
		}
		else
			echo('Cronjob LOADING. Jobname: '.$jobName);
	}

	public function logJob($jobName, $time, $loadCron){

		$logFile = 'cron-load-list';

		if( is_file($this->logDir.'/'.$this->toDay.'/'.$logFile) )
			$logData = file_get_contents($this->logDir.'/'.$this->toDay.'/'.$logFile);
		else
			$logData = '';

		$logData .= date('H:i:s')." job: ".$jobName." time: ".$time." loaded: ".($loadCron ? 'true' : 'false')."\n";

		file_put_contents($this->logDir.'/'.$this->toDay.'/'.$logFile, $logData);
	}

	private function endCron( $jobName ){

		if( is_file($this->logDir.'/'.$jobName) )
			unlink($this->logDir.'/'.$jobName);

		$this->toDayDir( $jobName );
		$end = '';
		if( is_file($this->logDir.'/'.$this->toDay.'/'.$jobName) )
			$end = file_get_contents($this->logDir.'/'.$this->toDay.'/'.$jobName);
		$end = str_replace('{end}', ' END '.date("H:i:s"), $end);
		file_put_contents($this->logDir.'/'.$this->toDay.'/'.$jobName, $end);
	}
}

?>