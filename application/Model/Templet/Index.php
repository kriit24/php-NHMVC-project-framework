<?
namespace Model\Templet;

class Index extends Controller{

	public function __construct(){

		if( _APPLICATION_ENV == 'admin' ){

			$this->inc( $this->toUrl(__DIR__) . '/inc/script.js' );
			$this->inc( $this->toUrl(__DIR__) . '/inc/style.css' );

			$this->Form = new Form;

			$this->backupIfNeeded();
		}
		if( $_GET['present'] ){

			$this->POST('addContent')->Action();
			$this->view('Index');
		}
	}

	protected function Index(){

		//$this->view('Index');
	}

	protected function Index_Admin(){

		$this->POST('update')->action();
		$this->POST('uploadZip')->action();
		$this->POST('addFile')->action();
		$this->POST('updateFiles')->action();
		$this->POST('updateDesign')->action();
		$this->POST('reUpdateDesign')->action('updateDesign');
		$this->POST('restoreDesign')->action();
		$this->POST('publishDesign')->action();
		$this->GET('action=deleteFile')->action();
		$this->GET('action=restoreFromBackup')->action();
		$this->GET('action=downloadManually')->action();

		$this->getTemplate()->view('Index_Admin');
	}

	public function CacheLayout(){

		pre($_POST);
	}
}

?>