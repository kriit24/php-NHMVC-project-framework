<?
namespace Model\Login;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'public' => array(
				'BoxLeftPublic' => array('position' => 'header-left', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
				'BoxRightPublic' => array('position' => 'header-right', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
			),
			'admin' => array(
				'BoxLeft' => array('position' => 'header-left', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
				'BoxRight' => array('position' => 'header-right', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
				'LoginBox' => array('position' => 'content', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
			),
			'method_name_without_template' => array()
		);
	}

	public static function privilege(): array{

		return array(
			'BoxLeft' => array('CLIENT', 'ADMIN', 'SUPERADMIN'),
			'BoxRight' => array('CLIENT', 'ADMIN', 'SUPERADMIN'),
			'LoginBox' => array('USER'),
			//'BoxLeftPublic' => array('CLIENT', 'ADMIN', 'SUPERADMIN'),
			'BoxRightPublic' => array('CLIENT', 'ADMIN', 'SUPERADMIN'),
		);
	}
	
	public static function template(): array{

		return array(
			'method-name' => 'template-some'
		);
	}
}

?>