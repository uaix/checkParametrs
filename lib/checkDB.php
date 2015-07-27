<?php
class BDException Extends Exception {

    function logErrors($errors) {
        echo parent::getMessage();
    }
}
 class checkDB {
    
    private $sql_login = '1';
    private $sql_password = '1';
    private $sql_host = 'localhost';
    private $sql_DB = '1';
    private $mysqli;
    private $table_name = 'MyGuests';
    private $table_name_2 = 'table_2';
    public $errors_run = array();
    public $passed_run = array();
    const CONNECT_BD = 0;
    const ADD_RECORD = 1;
    const EDIT_RECORD = 2;
    const DELETE_RECORD = 3;
    const ADD_INDEX = 4;
    const CREATE_TABLE = 5;
    const ADD_EXT_INDEX = 6;
    
    public static $errors = array(
            self::CONNECT_BD => 'Нет соединения с БД: ',
            self::CREATE_TABLE => 'БД создать не удалось',
            self::ADD_RECORD => 'НЕ удалось добавить запись в БД',
            self::EDIT_RECORD => 'Не удалось отредактировать запись в БД',
            self::DELETE_RECORD => 'Не удалось удалить запись в БД',
            self::ADD_INDEX => 'Не удалось добавить дополнительный индекс в БД',                 
            self::ADD_EXT_INDEX => 'Не удалось добавить внешний индекс'                 
            );
    
    public static $passed = array(
            self::CONNECT_BD => 'Соединение с БД успешно',
            self::CREATE_TABLE => 'БД создана',
            self::ADD_RECORD => 'Запись в БД успешно добавлена',
            self::EDIT_RECORD => 'Запись в БД успешно отредактирована',
            self::DELETE_RECORD => 'Запись в БД успешно удалена',
            self::ADD_INDEX => 'Индекс к БД успешно добавлен',                 
            self::ADD_EXT_INDEX => 'Внешний индекс успешно добавлен'                 
            );

    function __construct() {
        $this->mysqli = new mysqli($this->sql_host, $this->sql_login, $this->sql_password, $this->sql_DB);
    }
    
    function checkConnect() {
        try{
            if ($this->mysqli->connect_error){
		throw new BDException($this->getError(self::CONNECT_BD) .$this->mysqli->connect_error);
            }
            return $this->passed(self::CONNECT_BD);// TEST OK
        }
        catch (BDException $e){
            
	$e->logErrors($this->count_error);
        }
    }
    
    function createTable() {
        try{
            if (!$this->mysqli->query("CREATE TABLE IF NOT EXISTS $this->table_name (
                                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                                firstname VARCHAR(30) NOT NULL,
                                lastname VARCHAR(30) NOT NULL
                                )"))
            {
               throw new BDException($this->getError(self::CREATE_TABLE) .$this->mysqli->error);
            }
            return $this->passed(self::CREATE_TABLE);// TEST OK
        }
        catch (BDException $e) {
            $e->logErrors($this->count_error);
        }
    }
    
    function checkAddRecord() {
        try{
            if (!$this->mysqli->query("INSERT INTO $this->table_name (`lastname`)
                                    VALUES ('testlastname')"))
            {
               throw new BDException($this->getError(self::ADD_RECORD) .$this->mysqli->error);
            }
            return $this->passed(self::ADD_RECORD);// TEST OK
        }
        catch (BDException $e) {
            $e->logErrors($this->count_error);
        }
    }
    
    function checkEditRecord() {
        try{
            if (!$this->mysqli->query("UPDATE $this->table_name 
                                    SET lastname = 'testlastname_step2'
                                    WHERE lastname = 'testlastname'"))
            {
               throw new BDException($this->getError(self::EDIT_RECORD) .$this->mysqli->error);
            }
            return $this->passed(self::EDIT_RECORD);// TEST OK
        }
        catch (BDException $e) {
            $e->logErrors($this->count_error);
        }
    }
    
    function checkDeleteRecord() {
        try{
            if (!$this->mysqli->query("DELETE FROM $this->table_name
                                        WHERE lastname = 'testlastname_step2'"))
            {
               throw new BDException($this->getError(self::DELETE_RECORD) .$this->mysqli->error);
            }
            return $this->passed(self::DELETE_RECORD);// TEST OK
        }
        catch (BDException $e) {
            $e->logErrors($this->count_error);
        }
    }
    
    function checkAddIndex() {
        try{
            if (!$this->mysqli->query("ALTER TABLE $this->table_name ADD INDEX(`lastname`)"))
            {
               throw new BDException($this->getError(self::ADD_INDEX) .$this->mysqli->error);
            }
            return $this->passed(self::ADD_INDEX);// TEST OK
        }
        catch (BDException $e) {
            $e->logErrors($this->count_error);
        }
    }
    
    function AddExtIndex() {
        $this->mysqli->query("CREATE TABLE IF NOT EXISTS $this->table_name_2 (
                                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                                firstname VARCHAR(30) NOT NULL)");
        try{
            if (!$this->mysqli->query("ALTER TABLE $this->table_name_2
                                        ADD CONSTRAINT `FK_table_2_MyGuests` FOREIGN KEY (`id`) REFERENCES $this->table_name (`id`)"))
            {
               throw new BDException($this->getError(self::ADD_EXT_INDEX) .$this->mysqli->error);
            }
            return $this->passed(self::ADD_EXT_INDEX);// TEST OK
        }
        catch (BDException $e) {
            $e->logErrors($this->count_error);
        }
    }

    function getError($errorCode) {
        $this->errors_run[] = $errorCode;
    }
    
    function passed($okCode) {
        $this->passed_run[] = $okCode;
    }
    
    function run() {
        try{
                $this->checkConnect();
                if(!empty($this->errors_run)){
                    throw new BDException();
                }
                $this->createTable();
                $this->checkAddRecord();
                $this->checkEditRecord();
                $this->checkDeleteRecord();
                $this->checkAddIndex();
                $this->AddExtIndex();
            }
            catch (BDException $e){
            $e->logErrors($this->count_error);
            }

            $text_passed_code = array();
            $text_errors_code = array();

            //passed tests:
        if(!empty($this->passed_run)){
            foreach ($this->passed_run as $value) {
                $text_passed_code[] = self::$passed[$value];            
            }
        }

        //errors tests:
        if(!empty($this->errors_run)){
            foreach ($this->errors_run as $value) {
                $text_errors_code[] = self::$errors[$value];            
            }
        }
        $this->eraseTables($this->table_name_2);
        $this->eraseTables($this->table_name);
        return array_merge($text_passed_code,$text_errors_code);
    }
    function eraseTables($table_name) {
        $this->mysqli->query("DROP TABLE $table_name");
    }

}