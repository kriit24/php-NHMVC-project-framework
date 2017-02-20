<?
namespace Library;

class Bot{

	public static function is(){

		//for testing
		//return true;

		if( $_GET['robot'] ){

			\Library\Session::isRobot(true);
			if( $_GET['robot'] == 'false' )
				\Library\Session::clear('isRobot');
		}

		if( \Library\Session::isRobot() == true )
			return true;

		$bots = array(
			'abachobot',
			'acoon',
			'aesop_com_spiderman',
			'ah-ha.com crawler',
			'appie',
			'arachnoidea',
			'architextspider',
			'atomz',
			'deepindex',
			'esismartspider',
			'ezresult',
			'fast-webcrawler',
			'fido',
			'fluffy the spider',
			'googlebot',
			'gigabot',
			'gulliver',
			'gulper',
			'henrythemiragorobot',
			'ia_archiver',
			'kit-fireball/2.0',
			'lnspiderguy',
			'lycos_spider_(t-rex)',
			'mantraagent',
			'msn',
			'nationaldirectory-superspider',
			'nazilla',
			'openbot',
			'openfind piranha,shark',
			'scooter',
			'scrubby',
			'slurp.so/1.0',
			'slurp/2.0j',
			'slurp/2.0',
			'slurp/3.0',
			'tarantula',
			'teoma_agent1',
			'uk searcher spider',
			'webcrawler',
			'winona',
			'zyborg'
		);

		foreach($bots as $bot){

			if( strstr(strtolower($_SERVER['HTTP_USER_AGENT']), $bot) )
				return true;
		}

		if( in_array(strtolower($_SERVER['HTTP_USER_AGENT']), $bots) )
			return true;
		return false;
	}
}
?>