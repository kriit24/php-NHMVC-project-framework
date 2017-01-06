<?
namespace Command\Form;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'admin' => array(
				'Index_Admin' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => true),
			),
			'method_name_without_template' => array('is_route' => true),//without template - mostly for ajax
		);
	}

	public static function privilege(): array{

		return array(
			'Index_Admin' => array('SUPERADMIN'),
			'Dashboard' => array('SUPERADMIN'),
		);
	}
	
	public static function template(): array{

		return array(
			'method-name' => 'template-name'
		);
	}
}

?>