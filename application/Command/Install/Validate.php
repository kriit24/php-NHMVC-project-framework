<?
namespace Command\Install;

class Validate{

	public function isValidInstallAction(){

		$validate = new \Library\Validate;

		$validate->is_set('db_host')->message('Host is missing');
		$validate->is_set('db_port')->message('Port is missing');
		$validate->is_set('db_database')->message('Database is missing');
		$validate->is_set('db_user')->message('User is missing');
		$validate->is_set('db_password')->message('Password is missing');
		$validate->is_set('admin_user')->message('Administrator user is missing');
		$validate->is_set('admin_password')->message('Administrator password is missing');
		$validate->is_set('admin_email')->message('Administrator email is missing');
		$validate->in_array('default_language', \Conf\Conf::LANGUAGE)->message('Language not in list');
		return $validate->isValid('install', $_POST);
	}
}
?>