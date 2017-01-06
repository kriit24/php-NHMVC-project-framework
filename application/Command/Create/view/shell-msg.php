<?
if( $this->getMessage() )
	echo "\e[32m".implode("\n", $this->getMessage())."\e[0m\n";
if( $this->getError() )
	echo "\e[31m".implode("\n", $this->getError())."\e[0m\n";
?>