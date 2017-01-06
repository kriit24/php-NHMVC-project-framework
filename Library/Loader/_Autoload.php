<?
Library\Component\Cache::init();
Library\Loader\Library::init();
Library\Component\Register::register('ERROR', array(), Library\Component\Register::IS_ARRAY);
Library\Component\Register::register('MESSAGE', array(), Library\Component\Register::IS_ARRAY);
Library\Component\Register::register('VALIDATE_MESSAGE', array(), Library\Component\Register::IS_ARRAY);
Library\Component\Register::register('INCLUDES', array(), Library\Component\Register::IS_ARRAY);
Library\Component\Register::register('ROUTE', array(), Library\Component\Register::IS_ARRAY);
Library\Component\Register::register('LOADER', array(), Library\Component\Register::IS_ARRAY);
Library\Component\Register::register('HTTP_GET', array(), Library\Component\Register::IS_ARRAY);
Library\Component\Register::register('HTTP_POST', array(), Library\Component\Register::IS_ARRAY);
Library\Component\Router::init();
Library\Language::init();
Library\Register::init();
new Library\Auth();
?>