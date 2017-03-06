<?
namespace Model\Translate;

class Index extends Controller{

	public function __construct(){

		$this->inc( __DIR__ . '/inc/script.js' );
		$this->inc( __DIR__ . '/inc/style.css' );

		$this->Filter = new \Helper\Filter;
		$this->Paginator = new \Helper\Paginator;
		$this->Form = new Form;
		$this->Validate = new Validate;

		$this->language = new \Table\language;
	}

	protected function Index_Admin(){

		if( $this->Validate->isValidTranslate() )
			$this->POST(Form::SUBMIT['add'])->action();
		$this->GET('action=truncate')->action();
		$this->GET('action=delete')->action();

		$this->translateData()->view('Index');
	}

	protected function Edit(){

		$this->POST(Form::SUBMIT['update'])->action();

		$this->editData()->view('Edit');
	}

	protected function Dashboard(){

		$this->view('Dashboard');
	}

	protected function GetJsonTranslate(){

		echo json_encode($this->getTranslateByValue());
	}
}

?>