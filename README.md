# PHP FRAMEWORK named as: PROJECT FRAMEWORK #
namespace HMVC (as NHMVC), AUTOLOAD AS PSR version 4

### Introduction ###

* PROJECT FRAMEWORK is NHMVC framework wich has improved directory structure and AUTOLOAD
* Version: 4 beta

### What's the Difference ###

* builtin AUTOLOAD without additional composer
* builtin privileges and template positioning
* model structur is not strict

### DB support ###
* for now only for MariaDB or MySql

### PDO ###

* improved prepare statement, ->where("id = ? AND name = ?", array('id' => 1)); will not give error "second(name) parameter missing" it will remove name condition and continue with id condition only
* with madic method __call will be created column as "method" ->id(1)->fetch(); //will fetch query by id (SELECT * FROM table WHERE id = 1)
* EXAMPLES
* ->Select()->id(1)->fetch(); RESULT: SELECT * FROM table WHERE id = 1
* ->company('name')->zip(12)->Update(array(), "id = 5"); RESULT: UPDATE table SET company = 'name', zip = 12 WHERE id = 5
* ->id(1)->Delete(); RESULT: DELETE FROM table WHERE id = 1


### REDIS ###

* REDIS DB included


### Installation ###

* REQUIRED PHP 7 AND MYSQL 5.6
* Download from git
* Copy code to website OR make git checkout
* Start configuration in www.domain.com

### Contribution guidelines ###

* Application structure does not related with framework
* Full PHP namespace programming

### Improved Application SubDirectory  ###

* Default directory structur:
* Submodel
* Submodel/_Abstract.php for submodel register and privileges MOSTLY REQUIRED
* Submodel/Index.php for first contact to submodel REQUIRED
* all above NOT REQUIRED
* Submodel/Controller.php as controller
* Submodel/Form.php for forms
* Submodel/Validate.php for validate scripts
* Submodel/inc for includes like script.js and style.css
* Submodel/view for views
* build own directory structure if needed in the application folder

### AUTOLOAD ###

* AUTOLOAD no need for composer like composer.json, u can simply add "new class" without declaration.
* AUTOLOAD if some directory changes just add "extends \new\dir\location\and\file" to old file class "class old extends \new\dir\location\and\file"
* AUTOLOAD supports classname like "new dir1_dir2_file" and "new \dir1\dir2\file" and "new \dir1\dir2\dir3_file"
* AUTOLOAD supports short declaration full: "new \dir1\dir2\file", short: "new dir2\file" if IN same namespace - regular php namespace implementation
* AUTOLOAD would not include additional full directory listing automatically, what is downforce for loading.
* Examples included in project

### Updates ###

* on git pull - update only \Library directory

### Need help? ###

* help@projectpartner.ee
* contact if interested in the development