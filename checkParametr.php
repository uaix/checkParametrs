<?php
session_start();
?>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
<?php
function __autoload($class_name) {
    include 'lib/'.$class_name . '.php';
}

$object = new checkParametrs('localhost', '1', '1', '1');
$object->checkBD();
$object->checkSession();
$object->checkFiles();
$object->checkMemcache();
?>
    </body>
</html>


