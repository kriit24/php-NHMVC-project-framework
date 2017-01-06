<?
namespace Model\User;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'admin' => array(
				'Index_Admin' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => true),
			),
			'Edit' => array('is_route' => true),
			'Account' => array('is_route' => true),
		);
	}

	public static function privilege(): array{

		return array(
			'Index_Admin' => array('SUPERADMIN', 'ADMIN'),
			'Edit' => array('SUPERADMIN', 'ADMIN'),
			'Account' => array('*'),
		);
	}
	
	public static function template(): array{

		return array(
			'method-name' => 'template-name'
		);
	}
}

?>