<?
namespace Command\Form;

class Validate{

	public function isValidAddColumnAction(){

		$validate = new \Library\Validate;
		$validate->is_set('column_type')->message();
		$validate->is_set('column_name')->message();
		return $validate->isValid(Form::FORM['addColumn'], $_POST);
	}

	public function isValidUpdateColumnAction(){

		if( !$_POST['updateColumn'] )
			return false;

		$validate = new \Library\Validate;
		$isValid = true;
		foreach($_POST['update']['column_type'] as $k => $v){

			$column_type = $v;
			$column_name = $_POST['update']['column_name'][$k];
			$column_label = $_POST['update']['column_label'][$k];

			$check['column_type'] = $column_type;
			$check['column_name'] = $column_name;
			$check['column_label'] = $column_label;
			$check['update'] = true;

			$validate->is_set('column_type')->message();
			$validate->is_set('column_name')->message();
			if( !$validate->isValid('update', $check) )
				$isValid = false;
		}
		return $isValid;
	}

	public function isValidAddFormAction(){

		$validate = new \Library\Validate;
		$validate->is_set('route_name')->message();
		$validate->is_set('app_name')->message();
		$validate->is_set('form_name')->message();
		return $validate->isValid(Form::FORM['add'], $_POST);
	}
}
?>