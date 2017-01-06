<?

class ValitorClass{

	public function userExists($value){

		$db->query("SELECT 1 FROM user WHERE user_name = '".$value."' ");
		if( $db->numRows > 0 ){

			$this->error('User exists');
			return false;
		}
		return true;
	}
}

$library = new \Library;
$validate = new \Library\Validate;

$validate->is_set('some')//how to validate, array key name
	->message('Some not exists');//message to set if not valid

$validate->is_set('param')->message('{key} not exists');
$validate->is_match('param1', '/(a-zA-Z)/');//if message is not set then it sets default message
$validate->is_string('param1');
$validate->is_numeric('param1');
$validate->is_email('param1');
$validate->is_password('param1');
$validate->in_array('param1', array('data'));
$validate->is_equal('password_1', 'password_2');
$validate->validator( 'email', array(
	new ValitorClass, 'userExists'
) );

$validate->isValid('submit_name', $_POST);//array with data

pre($library->getError());

?>