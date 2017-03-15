<?
namespace Model\Menu;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'public' => array(
				'Index' => array('position' => 'header', 'is_static' => true, 'is_first_page' => false, 'is_route' => false)
			),
			'admin' => array(
				'Index_Admin' => array('position' => 'header', 'is_static' => true, 'is_first_page' => false, 'is_route' => false)
			),
			'method_name_without_template' => array()
		);
	}

	public static function privilege(): array{

		return array(
			'Index' => array('ADMIN', 'SUPERADMIN', 'MARKETING'),
			'Index_Admin' => array('ADMIN', 'SUPERADMIN')
		);
	}
	
	public static function template(): array{

		return array(
			'method-name' => 'template-name'
		);
	}
}

?>