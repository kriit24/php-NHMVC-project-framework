<?
namespace Model\Templet\Action;

abstract class update{

	public static function init(){

		$HTTP_POST = \Library\Http::POST();

		if( $_FILES['favicon']['size'] > 0 ){

			$data = \Library\Component\Cache::get('template');
			if( !$data['favicon'] )
				$_POST['favicon'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https:' : 'http:') . _URI .'/favicon?ver=1';

			move_uploaded_file( $_FILES['favicon']['tmp_name'], _DIR .'/' . $_FILES['favicon']['name'] );

			//http://projectpartner.ee/favicon.ico?ver=3
			$ver = 1;
			if( $data['favicon'] ){

				list(, $version) = explode('ver=', $data['favicon']);
				$ver = $version + 1;
			}
			$_POST['favicon'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https:' : 'http:') . _URI .'/' . $_FILES['favicon']['name'] .'?ver=' . $ver;
		}
		//die(pre($_POST));

		\Library\Component\Cache::set('template', $_POST['value']);
	}
}
?>