<?
namespace Command\Install;

class Index extends Controller{

	public function __construct(){

		$this->inc( __DIR__ . '/inc/style.css' );
	}

	protected function Index(){

		$this->Form = new Form;
		$this->Validate = new Validate;

		if( $this->Validate->isValidInstallAction() )
			$this->POST('install')->action();

		$this->dbFileExists()->gitignore()->view('Index');
	}
}
?>