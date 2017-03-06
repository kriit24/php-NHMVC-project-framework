<?
namespace Model\Translate;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'admin' => array(
				'Index_Admin' => array('position' => 'content', 'is_static' => false, 'is_first_page' => false, 'is_route' => true),
			),
			'Edit' => array('is_route' => true),
			'GetJsonTranslate' => array('is_route' => true),
		);
	}

	public static function privilege(): array{

		return array(
			'Index_Admin' => array('SUPERADMIN'),
			'Edit' => array('SUPERADMIN'),
			'Dashboard' => array('SUPERADMIN'),
			'GetJsonTranslate' => array('*'),
		);
	}

	public static function template(): array{

		return array(
			'method-name' => 'template-name'
		);
	}
}

?>