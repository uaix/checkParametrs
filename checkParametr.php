<?php
	$obj_session = new checkSession();
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
error_reporting(0);
function __autoload($class_name) {
    include dirname(__FILE__).DIRECTORY_SEPARATOR.'lib/'.$class_name . '.php';
}
function short_tags() {
    if (ini_get('short_open_tag') == 1){
        return 'включены';
    }
}
function check_mb_strlen() {
    if (function_exists('mb_strlen')) {
        return 'подключена';
    } else {
        return 'не подключена';
    }
}
echo 'Текущая версия PHP: ' . phpversion().'<br>Короткие теги: '.short_tags().'<br> Функция mb_strlen - '.check_mb_strlen().'<br>';

//TEST BD options
$obj_DB = new checkDB();
echo implode('<br>', $obj_DB->run()).'<br>';
//TEST Files options
$obj_files = new checkFiles();
echo $obj_files->run().'<br>';
//Session Test
echo implode('<br>', $obj_session->run()).'<br>';
//Memcache Test
$obj_Memcache = new checkMemcache();
echo implode('<br>',$obj_Memcache->run()).'<br>';
//Redis Test
$obj_Redis = new checkRedis();
echo $obj_Redis ->run();
?>
    </body>
</html>


