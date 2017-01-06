<?
namespace Library;

/**
* generates or reads xml data
*/
class Xml{

	use \Library\Component\Singleton;

	/**
	* get xml data from file or string
	* @param String $xmlfile AS filename with full location
	* @param String $xmldata='' AS xml string
	* @param Boolean $convertintoarray=true 
	* @return xml data
	*/
	function getXmlData($xmlFile, $xmlString='', $convertIntoArray=true){

		if( $xmlFile )
			return simplexml_load_file($xmlFile, 'SimpleXMLElement');
		if( $xmlString )
			return simplexml_load_string($xmlString, 'SimpleXMLElement');
    }

	/**
	* create xml from array
	* @param $data_array['list'] = 'val1';
	* @param $data_array['list']['item']['@attributes'] = array('id' => 1);
	* @param $data_array['list']['item']['name'] = 'company name';
	* @param $data_array['list']['item']['phone'] = '123';
	* @param $data_array['list']['type'] = 'company';
	* @param String $encoding='UTF-8'
	* @return xml string
	*/
	function createXmlData($data, $encoding='UTF-8'){

		$doc = new DOMDocument('1.0');
		// we want a nice output
		$doc->formatOutput = true;

		$root = $doc->createElement('book');
		$root = $doc->appendChild($root);

		$title = $doc->createElement('title');
		$title = $root->appendChild($title);

		$text = $doc->createTextNode('This is the title');
		$text = $title->appendChild($text);

		echo "Saving all the document:\n";
		echo $doc->saveXML() . "\n";

		echo "Saving only the title part:\n";
		echo $doc->saveXML($title);
	}
}

?>