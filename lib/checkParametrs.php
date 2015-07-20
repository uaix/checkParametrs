<?php
class checkParametrs {
    
    public $count_error  = 0;
    public $errors_text = '';
    
    function __construct() {
        
    }
    
    function checkBD($sql_host, $sql_username, $sql_password, $sql_dbname) {
            $mysqli = new mysqli($sql_host, $sql_username, $sql_password, $sql_dbname);
            if ($mysqli->connect_error){
                $this->count_error++;
                $this->errors_text .= 'Connect Error: ' . $mysqli->connect_error;
            }
            else{
                if ($mysqli->query("CREATE TEMPORARY TABLE myCity LIKE City") === TRUE) {
                printf("Таблица myCity успешно создана.\n");
                }
            }     
    }

}