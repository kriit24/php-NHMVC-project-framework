<?
namespace {namespace};

abstract class _Abstract implements \Library_Interface_Abstract{

	public static function register(): array{

		return array(
			'Index' => array('is_route' => true),
		);
	}

	public static function privilege(): array{

		return array(
			'Index' => array('*'),
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