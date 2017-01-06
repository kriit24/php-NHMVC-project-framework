<?
namespace Helper\Autocomplete;

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'Index' => array('is_route' => true),//without template - mostly for ajax
		);
	}

	public static function privilege(): array{

		return array(
			'Index' => array('*'),
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