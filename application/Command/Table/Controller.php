<?
namespace Command\Table;

class Controller extends \Library{

	public $selfError;

	public function indexData(){

		$this->tables = $this->checkInstalledTables(true);
		return $this;
	}

	public function create( $type ){

		switch ($type) {

			case 'Create':
				$msg = Action\create::init();
				if( isset($msg['msg']) )
					$this->message( $msg['msg'] );
				else
					$this->error( $msg['err'] );
			break;

			case 'Install':
				$msg = Action\install::init();
				if( isset($msg['msg']) )
					$this->message( $msg['msg'] );
				else
					$this->error( $msg['err'] );
			break;

			case 'Update':
			case 'Rebuild':
				$msg = Action\rebuild::init();
				if( isset($msg['msg']) )
					$this->message( $msg['msg'] );
				else
					$this->error( $msg['err'] );
			break;
		}
	}

	public function checkInstalledTables( $return = false ){

		list($tables, $error) = Action\checkInstalledTables::get( $return );
		$this->selfError = $error;
		if( $return )
			return $tables;
		return $this;
	}
}

?>