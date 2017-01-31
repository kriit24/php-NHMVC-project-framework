<?
namespace Model\Privilege;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'admin' => array(
				'Index_Admin' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => true)
			),
			//'getClass' => array('is_route' => true),
			//'getMethod' => array('is_route' => true),
		);
	}

	public static function privilege(): array{

		return array(
			'Index_Admin' => array('SUPERADMIN'),
			//'getClass' => array('SUPERADMIN'),
			//'getMethod' => array('SUPERADMIN')
		);
	}
	
	public static function template(): array{

		return array(
			'method-name' => 'template-name'
		);
	}
}

?>