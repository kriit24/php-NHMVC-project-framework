<?
namespace Command\Install;

class Controller extends \Library{

	public function dbFileExists(){

		if( !is_file(__DIR__.'/inc/database.sql') )
			$this->error('DB file missing');
		return $this;
	}

	public function gitignore(){

		return $this;
	}

	public function install(){

		if( Action\isInstalled::get() )
			return true;

		Action\install::init();
	}
}
?>