if data is json it will fire callback
<?
$this->script('
$.canJSON(data, function(arrayData){

	alert(arrayData);
});
');
?>