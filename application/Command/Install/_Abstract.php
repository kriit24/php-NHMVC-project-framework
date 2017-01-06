<?
namespace Command\Install;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'public' => array(
				'Index' => array('position' => 'content', 'is_static' => false, 'is_first_page' => true, 'is_route' => true)
			)
		);
	}

	public static function privilege(): array{

		return array(
			'Index' => array('NOT-INSTALLED')
		);
	}
	
	public static function template(): array{

		return array(
			'method-name' => 'template-name'
		);
	}
}

?>