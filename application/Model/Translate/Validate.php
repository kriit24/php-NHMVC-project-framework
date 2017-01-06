<?
namespace Model\Translate;

class Validate{

	public function isValidTranslate(){

		$validate = new \Library\Validate;
		$validate->is_set('name')->message('is required');
		$validate->is_set('method')->message('is required');
		return $validate->isValid(Form::SUBMIT['add'], $_POST);
	}
}
?>