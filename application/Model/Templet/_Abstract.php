<?
namespace Model\Templet;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'public' => array(
				'Index' => array('position' => 'content', 'is_static' => true, 'is_first_page' => false, 'is_route' => false),
			),
			'admin' => array(
				'Index_Admin' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => true),
			),
			'CacheLayout' => array('is_route' => true),
		);
	}

	public static function privilege(): array{

		return array(
			'Index_Admin' => array('ADMIN', 'SUPERADMIN'),
			'CacheLayout' => array('ADMIN', 'SUPERADMIN'),
			'Index' => array('ADMIN', 'SUPERADMIN'),
		);
	}

	public static function template(): array{

		return array(
			'method-name' => 'template-name'
		);
	}
}

?>