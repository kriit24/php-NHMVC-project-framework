<?
namespace Library\DB\REDIS;

class Redis extends Connection{

	public $Redis;
	use Query;

	function __construct(){

		$this->Redis = $this->getConnection();
		$this->construct();
	}

	public function setConnection(){

		return $this->setConn();
	}

	public function getConnection(){

		return $this->getConn();
	}
}
?>