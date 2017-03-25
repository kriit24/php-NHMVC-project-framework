<?
namespace Helper\Form;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'public' => array(
				'Index' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => false),//if is_route is false, then cannot access over url
			),
			'admin' => array(
				'Index_Admin' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => true),
			),
		);
	}

	public static function privilege(): array{

		return array(
			//'Index_Admin' => array('SUPERADMIN'),
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