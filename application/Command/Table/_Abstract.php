<?
namespace Command\Table;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'admin' => array(
				'Index_Admin' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => true),
				'Check' => array('position' => 'header', 'is_static' => true, 'is_first_page' => false, 'is_route' => false)
			)
		);
	}

	public static function privilege(): array{

		return array(
			'Index_Admin' => array('SUPERADMIN'),
			'Check' => array('SUPERADMIN')
		);
	}
	
	public static function template(): array{

		return array(
			'method-name' => 'template-name'
		);
	}
}

?>