<?
namespace Command\Install;

class Form extends \Library{

	public function installForm(){

		$form = new \Library\Form( 'list' );
		$form->addElem('form', '', array(
			'class' => 'install-form'
		));
		$form->addElem('data', 'DB', array(
			'label' => _tr('Database'),
			'label-attr' => array('style' => 'color:black;text-transform: uppercase;')
		));
		$form->addElem('text', 'db_host', array(
			'label' => _tr('Host')
		));
		/*$form->addElem('select', 'db_driver', array(
			'label' => _tr('Driver'),
			'option' => PDO::getAvailableDrivers()
		));*/
		$form->addElem('text', 'db_port', array(
			'label' => _tr('Port'),
			'value' => 3306
		));
		$form->addElem('text', 'db_database', array(
			'label' => _tr('Database')
		));
		$form->addElem('text', 'db_user', array(
			'label' => _tr('Database user')
		));
		$form->addElem('password', 'db_password', array(
			'label' => _tr('Database password')
		));

		$form->addElem('data', 'REDIS', array(
			'label' => _tr('Redis'),
			'label-attr' => array('style' => 'color:black;text-transform: uppercase;')
		));
		$form->addElem('text', 'redis_host', array(
			'label' => _tr('Host')
		));
		$form->addElem('text', 'redis_port', array(
			'label' => _tr('Port'),
			'value' => 6379
		));
		$form->addElem('password', 'redis_password', array(
			'label' => _tr('Database password')
		));
		$form->addElem('select', 'default_language', array(
			'label' => _tr('Language'),
			'option' => $this->arrayValueToKey(\Conf\Conf::LANGUAGE)
		));


		$form->addElem('data', 'USER', array(
			'label' => _tr('User'),
			'label-attr' => array('style' => 'color:black;text-transform: uppercase;')
		));
		$form->addElem('text', 'admin_user', array(
			'label' => _tr('Admin user')
		));
		$form->addElem('password', 'admin_password', array(
			'label' => _tr('Admin password')
		));
		$form->addElem('text', 'admin_email', array(
			'label' => _tr('Admin email')
		));
		$form->addElem('submit', 'install', 'Install');

		$form->errorLabel( $this->getError() );
		$form->setData( $_POST );
		$form->toString();

		return $form;
	}
}

?>