<?
namespace Model\Role;

class Validate{

	public function isValidRole(){

		$validate = new \Library\Validate;
		$validate->is_set('name')->message('Name is required');
		$validate->is_set('level')->message('Level is required');
		$validate->is_set('type')->message('Type is required');
		return $validate->isValid(Form::SUBMIT['add'], $_POST);
	}

	public function isValidUpdateRole(){

		$validate = new \Library\Validate;
		$validate->is_set('level')->message('Level is required');
		$validate->is_set('type')->message('Type is required');
		return $validate->isValid(Form::SUBMIT['update'], $_POST);
	}
}
?>