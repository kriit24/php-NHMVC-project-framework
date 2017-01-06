<?
namespace Model\Example;

abstract class _Abstract implements \Library_Interface_Abstract{

	function register(): array{

		return array(
			'public' => array(
				'Index' => array('position' => 'content', 'is_static' => false, 'is_first_page' => true, 'is_route' => true),//if is_route is false, then cannot access over url
			),
			'admin' => array(
				'Index_Admin' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => true),
			),
			'method_name_without_template' => array('is_route' => true),
		);
	}

	function privileges(): array{

		return array(
			'Dashboard' => array('SUPERADMIN'),//dashboard content privileges
		);
	}
}

?>