<?
namespace Model\Dashboard;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'public' => array(
				//'Index' => array('position' => 'content', 'is_static' => false, 'is_first_page' => true, 'is_route' => false),
				//'Header' => array('position' => 'content', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
			),
			'admin' => array(
				'Index' => array('position' => 'content', 'is_static' => false, 'is_first_page' => true, 'is_route' => false),
				'Header' => array('position' => 'content', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
			),
			'method_name_without_template' => array()
		);
	}

	public static function privilege(): array{

		return array(
			'Index' => array('ADMIN', 'SUPERADMIN'),
			'Header' => array('*'),
		);
	}
	
	public static function template(): array{

		return array(
			'method-name' => 'template-name'
		);
	}
}

?>