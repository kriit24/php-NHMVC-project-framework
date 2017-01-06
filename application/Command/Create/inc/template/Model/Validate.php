<?
namespace {namespace};

class Validate{

	public function __construct(){

		$this->Validate = new \Library\Validate;
	}

	public function validateIndexData(){

		$validate = $this->Validate;
		$validate->is_set('name')->message();
		return $validate->isValid(Form::SUBMIT['add'], $_POST);
	}
}

?>