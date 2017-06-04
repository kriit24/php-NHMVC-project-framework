<?
namespace Command\Patch;

class Index{

	public function __construct(){

		//$this->inc( __DIR__ . '/inc/script.js' );
		//$this->inc( __DIR__ . '/inc/style.css' );
	}

	protected function Index(){

		echo 'Command\Patch / Index';
	}

	protected function Index_Admin(){

		echo 'Command\Patch / IndexAdmin';
	}

	public static function shell($command){

		$c = new Controller;
		$c->command = $command;
		$c->PatchDb();

		$c->view('shell');
	}
}

?>