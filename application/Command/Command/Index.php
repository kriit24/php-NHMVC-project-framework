<?
namespace Command\Command;

class Index extends \Library{

	protected function Index(){

		if( $_GET['method'] == 'Index' || !$_GET['method'] )
			$this->view('help');
	}

	protected function Help(){

		$this->view('help');
	}

	protected function Create(){

		\Command\Create\Index::shell( Params::get() );
	}

	protected function Table(){

		\Command\Table\Index::shell( Params::get() );
	}

	protected function Build(){

		\Command\Build\Index::shell( Params::get() );
	}
}

?>