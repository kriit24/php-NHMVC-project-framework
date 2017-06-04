<?
namespace Command\Command;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'Index' => array('is_route' => false),
			'Build' => array('is_route' => false),
			'Help' => array('is_route' => false),
			'Create' => array('is_route' => false),
			'Table' => array('is_route' => false),
			'Patch' => array('is_route' => false),
		);
	}

	public static function privilege(): array{

		return array(
			'Index' => array('*'),
			'Build' => array('*'),
			'Help' => array('*'),
			'Create' => array('*'),
			'Table' => array('*'),
			'Patch' => array('*'),
		);
	}
	
	public static function template(): array{

		return array(
			'method-name' => 'template-name'
		);
	}
}

?>