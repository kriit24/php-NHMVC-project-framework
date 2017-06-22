<?
namespace Command\Git;

class Index{

	public function __construct(){

		//$this->inc( __DIR__ . '/inc/script.js' );
		//$this->inc( __DIR__ . '/inc/style.css' );
	}

	protected function Index(){

		echo 'Command\Git / Index';
	}

	protected function Index_Admin(){

		echo 'Command\Git / IndexAdmin';
	}

	public static function shell($command){

		$c = new Controller;
		$c->command = $command;
		$c->push();

		$c->view('shell');
	}
}

?>