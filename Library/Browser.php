<?
namespace Library;

class Browser{

	static function get(){

		$browser = new Component\Browser;
		return array( 
			'browser' => $browser->getBrowser(),
			'short' => preg_replace('/(\B.|\s+)/', '', $browser->getBrowser()),
			'version' => $browser->getVersion(),
			'aolVersion' => $browser->getAolVersion(),
			'user-agent' => $browser->getUserAgent(),
			'platform' => $browser->getPlatform()
		);
	}
}
?>