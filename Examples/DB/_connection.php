<?
//STEP 1 - config connections IN application/Conf/Conf.php

	const _DB_CONN = array(
		'default' => array('_host' => '', '_port' => '', '_database' => '', '_user' => '', '_password' => '', '_driver' => 'mysql'),
		'conn_988' => array('_host' => '', '_port' => '', '_database' => '', '_user' => '', '_password' => '', '_driver' => 'mysql'),
	);

//STEP 2 - set connection IN application/_Autoload.php

\Library\Sql::singleton()->setConnection(); //- set connection by conf

//STEP 3 - get connection

//pure sql connect
$sql3 = new \Library\Sql('conn_988');

//OR in application/Table(table_name.php

namespace Table;

class language extends \Library\Sql{

	protected $_name = 'language';
	protected $_validFields = array('id', 'name', 'value', 'language', 'model');
	protected $_integerFields = array('id');

	function __construct(){

		parent::__construct( 'conn_988' );
	}
}
?>