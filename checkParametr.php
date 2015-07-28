<?php
	$obj_session = new CheckSession();
	$obj_session->checkSession();
?>
<html>
    <head>
        <title>Проверка хостинга на соответствие требованиям</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
<?php
function __autoload($class_name) {
    include 'lib/'.$class_name . '.php';
}
echo 'Текущая версия PHP: ' . phpversion().'<br>Короткие теги: '.  ini_get('SMTP').'<br>';
echo $obj_session->run();
/*$object->checkSession();
$object->checkFiles();
$object->checkMemcache();
$object->checkRedis();*/
?>
    </body>
</html>


