<?
namespace Api\Example;

abstract class _Abstract implements \Library_Interface_Abstract{

	function register(): array{

		return array(
			'public' => array(
				'Example' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => true),//if is_route is false, then cannot access over url
			),
			'admin' => array(
				'Example_Admin' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => true),
			),
			'method_name_without_template' => array('is_route' => true),//mostly ajax reguests
		);
	}

	function privilege(): array{

		return array(
			'Example' => array('SUPERADMIN'),
			'Example_Admin' => array('SUPERADMIN'),
			'Dashboard' => array('SUPERADMIN'),
		);
	}

	function template(): array{

		return array(
			'method-name' => 'template-name'//application/Template/template-name
		);
	}
}

?>