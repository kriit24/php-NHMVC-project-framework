<?PHP

//if($_SESSION['userData']['type'] != 'SUPERADMIN')
//die('<center>Uuendamisel</center>');

set_include_path(__DIR__);
define('_APPLICATION_ENV', 'public');
define('_APPLICATION_PATH', 'application');

require _APPLICATION_PATH.'/Conf/Conf.php';
require 'Library/Loader/Abstract.php';

new class extends Application{};

?>