<?PHP
namespace Package;
/**
* generates pdf file
*/
class Pdf{

	private $pdf;

	function __construct(){

		require_once __DIR__.'/Dompdf/dompdf_config.inc.php';
	}

	function setHtml( $html ){

		$this->loadClass();
		$this->pdf->load_html( $html );
		$this->render();
	}

	function setFile( $file ){

		$this->loadClass();
		$this->pdf->load_html_file( $file );
		$this->render();
	}

	function show(){

		$this->pdf->stream( tempnam(uniqid(rand(), true)) );
		$this->destruct();
	}

	function write( $file_name ){

		file_put_contents( $file_name, $this->pdf->output() );
		$this->destruct();
	}

	private function loadClass(){

		\Autoload::unregister();
		$this->pdf = new \DOMPDF();
	}

	private function render(){

		$this->pdf->set_paper('a4', 'portrait');
		$this->pdf->render();
	}

	function destruct(){

		\Autoload::register();
	}
}
?>