<?
namespace Model\Language;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'public' => array(
				'Index' => array('position' => 'header-bottom', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),//if is_route is false, then cannot access over url
			),
			'admin' => array(
				'Index_Admin' => array('position' => 'header-bottom', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),//if is_route is false, then cannot access over url
			),
			'method_name_without_template' => array('is_route' => true),//without template - mostly for ajax
		);
	}

	public static function privilege(): array{

		return array(
			'Index' => array('*'),
			'Index_Admin' => array('ADMIN', 'SUPERADMIN'),
			//'Dashboard' => array('SUPERADMIN'),
		);
	}
	
	//works only by route $_GET
	public static function template(): array{

		return array(
			'method-name' => 'template-name'
		);
	}
}

?>