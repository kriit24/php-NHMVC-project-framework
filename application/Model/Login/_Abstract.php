<?
namespace Model\Login;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'public' => array(
				'LoggedBoxLeftPublic' => array('position' => 'header-left', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
				'LoggedBoxRightPublic' => array('position' => 'header-right', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
			),
			'admin' => array(
				'LoggedBoxLeftAdmin' => array('position' => 'header-left', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
				'LoggedBoxRightAdmin' => array('position' => 'header-right', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
				'LoginBoxAdmin' => array('position' => 'content', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
			),
			'method_name_without_template' => array()
		);
	}

	public static function privilege(): array{

		return array(
			'LoggedBoxLeftAdmin' => array('CLIENT', 'ADMIN', 'SUPERADMIN'),
			'LoggedBoxRightAdmin' => array('CLIENT', 'ADMIN', 'SUPERADMIN'),
			'LoginBoxAdmin' => array('USER'),
			//'LoggedLoggedBoxLeftPublic' => array('CLIENT', 'ADMIN', 'SUPERADMIN'),
			'LoggedBoxLeftPublic' => array('CLIENT', 'ADMIN', 'SUPERADMIN'),
		);
	}
	
	public static function template(): array{

		return array(
			'method-name' => 'template-some'
		);
	}
}

?>