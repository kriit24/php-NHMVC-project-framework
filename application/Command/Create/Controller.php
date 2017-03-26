<?
namespace Command\Create;

class Controller extends \Library{

	public function getCommands(){

		return $this->scandir( $this->dir(dirname(__DIR__, 2).'/Command'), false, '', true );
	}

	public function getModelsByFolder(){

		$this->scanDir = $this->scandir( _DIR.'/application/'.$_GET['folder'], false, '', true );
		return $this;
	}

	public function getColumnsData(){

		$db = \Conf\Conf::_DB_CONN['_default']['_database'];
		$sql = new \Library\Sql;

		die(json_encode(\Library\ArrayIterator::singleton()->arrayValues($sql->Query("SHOW COLUMNS FROM ".$db.".".$_GET['table'])->fetchAll(), 'Field')));
	}

	public function saveColumnsData(){

		if( !$_POST['table_column'] )
			return;

		$col = array($_POST['table'] => $_POST['table_column']);
		\Session::tableColumns( array($_POST['table'] => $_POST['table_column']) );
		$col = \Session::tableColumns(true);

		$array = array();

		foreach($col as $v){

			foreach($v as $v1){

				if( !in_array($v1, $array) )
					$array[] = $v1;
			}
		}

		\Session::columnsData( $array );
	}

	public function addMethod(){

		$this->create( $_POST['create'] );
	}

	public function create( $type ){

		switch ($type) {

			case 'Model':
				if( Action\createModel::init( $_POST['folder'] ) )
					$this->message('Submodel created/updated');
				else
					$this->error('Submodel allready exists');
			break;

			case 'Basic':
				if( Action\createModel::init( 'Basic' ) )
					$this->message('Submodel created/updated');
				else
					$this->error('Submodel allready exists');
			break;

			case 'Method':
				if( Action\createMethod::init() )
					$this->message(
						'Method created: {method}<br>Now SET regsiter AND privileges ON {on}',
						array('method' => basename($_POST['name']), 'on' => '<b style="color:red;">/application/'.$_POST['folder'].'/'.$_POST['model_name'].'/_Abstract.php</b>')
					);
				else
					$this->error('Method allready exists: {method}', array('method' => basename($_POST['name'])));
			break;

			case 'Action':
				if( Action\createAction::init() )
					$this->message('Action created');
				else
					$this->error('Action allready exists');
			break;

			default:
				if( Action\createModel::init( $_POST['folder'] ) )
					$this->message('Submodel created/updated');
				else
					$this->error('Submodel allready exists');
			break;
		}
	}
}

?>