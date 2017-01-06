<?
namespace Command\Create;

class Validate extends \Library{

	public function folderExists( $value ){

		if( !is_dir( _DIR . '/'. _APPLICATION_PATH .'/'. $value ) ){

			$this->error('Application Folder does not exists');
			return false;
		}
		return true;
	}

	public function isValidAddMethodAction(){

		$validate = new \Library\Validate;

		$validate->is_set('folder')->message();
		$validate->is_set('name')->message();
		$validate->validator( 'folder', array(
			new \Command\Create\Validate, 'folderExists'
		) );
		return $validate->isValid('addMethod', $_POST);
	}
}
?>