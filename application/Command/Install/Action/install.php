<?
namespace Command\Install\Action;

abstract class install{

	public static function init(){

		$conn = array('_host' => $_POST['db_host'], '_port' => $_POST['db_port'], '_database' => $_POST['db_database'], '_user' => $_POST['db_user'], '_password' => $_POST['db_password'], '_driver' => 'mysql');
		$db = new \Library\Sql();
		$db->connect($conn);

		$dbConnected = $db->isConnected();
		
		$conn2 = array('_host' => $_POST['redis_host'], '_port' => $_POST['redis_port'], '_password' => $_POST['redis_password']);
		$redis;
		$redisConnected = true;

		if( $_POST['redis_host'] && $_POST['redis_password'] ){

			$redisConnected = false;
			$redis = new \Library\Redis( $_POST['db_database'] );
			$redis->connect($conn2);
			$redisConnected = $redis->isConnected();
		}
		$library = new \Library;

		if( $dbConnected && $redisConnected ){

			self::updatePhpMyadminConf();

			$db->query("SHOW TABLES FROM `".$_POST['db_database']."`");
			if( $db->Numrows() == 0){

				self::installDb( $db, $redis );
				self::updateConf($conn, $conn2, $redisConnected);
				self::sendEmail();
				\Session::clear('userData');
				\Session::clear('initiated');
				sleep(2);//wait 2 seconds to write conf.php file
				die(\Library\Http::redirect('/admin'));
			}
			else{

				$library->error('Database allready installed');
			}
		}
	}

	private static function installDb($db, $redis){

		$dbString = file_get_contents( dirname(__DIR__).'/inc/database.sql' );
		foreach(array('CREATE TABLE' => '\;', 'INSERT INTO' => '\;', 'UPDATE' => '\;', 'CREATE TRIGGER' => 'END\;', 'CREATE PROCEDURE' => 'END\;', 'CREATE FUNCTION' => 'END\;', 'CREATE DEFINER' => 'END\;') as $startLine => $endLine){

			preg_match_all('/\n'.$startLine.'(.*?)'.$endLine.'/s', $dbString, $matches);
			foreach($matches[0] as $query){

				if( $query )
					$db->query($query);
			}
		}
		$db->query("INSERT INTO user (role_id, name, password, type) VALUES (1, '".$_POST['admin_user']."', '".md5($_POST['admin_password'])."', 'SUPERADMIN')");
		$db->query("INSERT INTO client (user_id, first_name) VALUES (".$db->Insertid().", '".$_POST['admin_user']."')");

		if( $redis->isConnected() )
			$redis->delete('language');

		unlink(dirname(__DIR__).'/inc/database.sql');
	}

	private static function updatePhpMyadminConf(){

		$phpMyadminConfig = dirname(__DIR__, 3).'/Package/phpMyAdmin/libraries/config.default.php';

		if( is_file($phpMyadminConfig) ){

			$content = file_get_contents($phpMyadminConfig);
			if( preg_match('\'localhost\'', $content) ){

				$content = str_replace('localhost', $_POST['db_host'], $content);
				file_put_contents($phpMyadminConfig, $content);
			}
		}
	}

	private static function updateConf($conn, $conn2, $redisConnected){

		$connString = '';
		$post = $_POST;
		$post['db_driver'] = 'mysql';
		foreach( array('_host', '_port', '_database', '_user', '_password', '_driver') as $key )
			$connString .= $connString ? ", '".$key."' => '".$post['db' . $key]."'" : "'".$key."' => '".$post['db' . $key]."'";

		$connString2 = '';
		foreach( array('_host', '_port', '_password') as $key )
			$connString2 .= $connString2 ? ", '".$key."' => '".$post['redis' . $key]."'" : "'".$key."' => '".$post['redis' . $key]."'";

		$content = file_get_contents(dirname(__DIR__, 3) .'/Conf/Conf.php');
		$content = str_replace("const _DB_CONN = false;", "const _DB_CONN = array(\n\t\t'_default' => array(".$connString.")\n\t);", $content);
		if( $redisConnected )
			$content = str_replace("const _DB_REDIS = false;", "const _DB_REDIS = array(".$connString2.");", $content);
		$content = str_replace("const _EMAIL = false;", "const _EMAIL = '".$_POST['admin_email']."';", $content);
		$content = str_replace("const _DLANG = false;", "const _DLANG = '".$_POST['default_language']."';", $content);
		file_put_contents(dirname(__DIR__, 3) .'/Conf/Conf.php', $content);
	}

	private static function sendEmail(){

		$emailExp = explode('@', $_POST['admin_email']);
		$SERVER_NAME_EXP = explode('.', $_SERVER['SERVER_NAME']);

		$emailer = new \Package\Email;
		$emailer->setToName( $emailExp[0] );
		$emailer->setToEmail( $_POST['admin_email'] );
		$emailer->setFromName( $_SERVER['SERVER_NAME'] );
		$emailer->setFromEmail( 'web@' . $SERVER_NAME_EXP[1] .'.'. $SERVER_NAME_EXP[2] );
		$emailer->setSubject( 'Website installed: '.$_SERVER['SERVER_NAME'] );
		$emailer->setContent( 'Website installed: '.$_SERVER['SERVER_NAME'].'<br/>'.'<br/>USER:'.$_POST['admin_user'].'<br/>PASSWORD:'.substr($_POST['admin_password'], 0, -3).'***' );
		$emailer->sendEmail();
	}
}
?>