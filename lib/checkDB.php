<?php
class BDException Extends Exception {

    function logErrors($errors) {
        echo parent::getMessage();
    }
}
 class SessionException Extends Exception {
     
     function logErrors($errors) {
        echo parent::getMessage().'<br>Количество ошибок: '.$errors.'<br>';
    }
         
 }
 class FilesException Extends Exception {
     
     function logErrors($errors) {
        echo parent::getMessage().'<br>Количество ошибок: '.$errors.'<br>';
    }
         
 }
  class MException Extends Exception {
     
     function logErrors($errors) {
        echo parent::getMessage().'<br>Количество ошибок: '.$errors.'<br>';
    }
         
 }
 class checkDB {
    
    private $sql_login = '1';
    private $sql_password = '121';
    private $sql_host = 'localhost';
    private $sql_DB = '1';
    private $mysqli;
    private $table_name = 'MyGuests';
    public $errors_run = array();
    public $passed_run = array();
    const CONNECT_BD = 0;
    const ADD_RECORD = 1;
    const EDIT_RECORD = 2;
    const DELETE_RECORD = 3;
    const ADD_INDEX = 4;
    const CREATE_TABLE = 5;
    
    public static $errors = array(
            self::CONNECT_BD => 'Нет соединения с БД: ',
            self::CREATE_TABLE => 'БД создать не удалось',
            self::ADD_RECORD => 'НЕ удалось добавить запись в БД',
            self::EDIT_RECORD => 'Не удалось отредактировать запись в БД',
            self::DELETE_RECORD => 'Не удалось удалить запись в БД',
            self::ADD_INDEX => 'Не удалось добавить дополнительный индекс в БД'                 
            );
    
    public static $passed = array(
            self::CONNECT_BD => 'Соединение с БД успешно',
            self::CREATE_TABLE => 'БД создана',
            self::ADD_RECORD => 'Запись в БД успешно добавлена',
            self::EDIT_RECORD => 'Запись в БД успешно отредактирована',
            self::DELETE_RECORD => 'Запись в БД успешно удалена',
            self::ADD_INDEX => 'Индекс к БД успешно добавлен'                 
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
                                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
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
                throw new BDException('<br>Ошибка соединения с БД');
            }
            $this->createTable();
            $this->checkAddRecord();
            $this->checkEditRecord();
            $this->checkDeleteRecord();
            $this->checkAddIndex();
            
        }
        catch (BDException $e){
            
	$e->logErrors($this->count_error);
        }
        
        //passed tests:
    if(!empty($this->passed_run)){
        $passed_tests = '<H2>Успешно пройденные тесты:</H2> <br>';
        foreach ($this->passed_run as $value) {
            $passed_tests .= self::$passed[$value]."<br> \n";
        }
        return $passed_tests;
    }
    
    //errors tests:
    if(!empty($this->errors_run)){
        $errors_tests = '<H2>Не пройденные тесты:</H2> <br>';
        foreach ($this->errors_run as $value) {
            $errors_tests .= self::$errors[$value]."<br> \n";
        }
        return $errors_tests;
    }
}

}
 /*
class checkParametrs {
    
    private $count_error  = 0;
    private $sql_login = '1';
    private $sql_password = '1';
    private $sql_host = 'localhost';
    private $sql_DB = '1';
    private $mysqli;
    private $connect_BD = 'Тест соединения с БД - Не проверялось';
    private $create_table = 'Тест создания таблицы -  Не проверялось';
    private $add_record = 'Тест создания новой записи -  Не проверялось';
    private $edit_record = 'Тест редактирования записи -  Не проверялось';
    private $delete_record = 'Тест удаления записи -  Не проверялось';
    private $add_index = 'Тест добавления индекса -  Не проверялось';
            
    function __construct() {
         $this->mysqli = new mysqli($this->sql_host, $this->sql_login, $this->sql_password, $this->sql_DB);
    }
            
    function check_mysql_query($query,$type_query,$text_query) {
        if (!$this->mysqli->query($query)){
                $this->$type_query = '<span class="error">'.$text_query.' - не пройден</span>';
                $this->count_error++;
                throw new BDException('Error testing BD: ' . $this->mysqli->error);
                    }
        $this->$type_query = '<span class="check">'.$text_query.' - пройден</span>';            
    }
    
    function checkBD() {
                
        try{
                        
            //cheking connect to BD
            
            if ($this->mysqli->connect_error){
                $this->connect_BD = '<span class="error">Тест соединения с БД - не пройден</span>';
                $this->count_error++;
		throw new BDException('Ошибка соединения с БД: ' . $mysqli->connect_error);
            }
            $this->connect_BD = '<span class="check">Тест соединения с БД - пройден</span>'; // TEST OK
            
                //Cheking create Table
            $this->check_mysql_query("CREATE TABLE IF NOT EXISTS $table_name (
                                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                firstname VARCHAR(30) NOT NULL,
                                lastname VARCHAR(30) NOT NULL
                                )",'create_table', 'Тест создания таблицы');
            
            //Checking add record
            
            $this->check_mysql_query("INSERT INTO $table_name (`lastname`)
                                    VALUES ('testlastname') ",'add_record','Тест добавления записи');
            
            //Checking update record
            
            $this->check_mysql_query("UPDATE $table_name 
                                    SET lastname = 'testlastname_step2'
                                    WHERE lastname = 'testlastname'
                                    ",'edit_record','Тест редактирования записи');
             
            //Checking delete record
            
            $this->check_mysql_query("DELETE FROM $table_name
                                        WHERE lastname = 'testlastname_step2'
                                    ",'delete_record','Тест удаления записи');
            //Checking add Index
            
            $this->check_mysql_query("ALTER TABLE $table_name ADD INDEX(`lastname`)
                                    ",'add_index','Тест добавления индекса');
           
            
        }
        catch (BDException $e){
            
	$e->logErrors($this->count_error);
        }
        echo 'Тест MYSQL данных: <br>'.$this->connect_BD.'<br>'
                .$this->create_table.'<br>'.$this->add_record.'<br>'
                .$this->edit_record.'<br>'.$this->delete_record.'<br>'
                .$this->add_index.'<br>';
    }
    
    function checkSession() {
        if(!isset($_SESSION['test']))
            {
            try {
                  if(!$_SESSION['test']='test_session') throw new SessionException('Ошибка создания сессии');
            } 
            catch (SessionException $e){
                    $this->count_error++;
                    echo $e->logErrors($this->count_error);
            }
                echo 'Сессия успешно создана!<br>';
            }
            else echo 'Сессия уже существует со значением - '.$_SESSION['test'].'<br>';
        }
        
    function checkFiles() {

        $path = realpath($_SERVER['DOCUMENT_ROOT'].'../../');
        try {
                if(file_put_contents($path.'/test.txt', 'test_content')===FALSE){
                    $this->count_error++;
                    throw new FilesException('Тест записи в файл - не пройден<br>');
                }
            echo 'Тест записи в файл - пройден<br>';   
        }
        catch (FilesException $e) {
                $e->logErrors($this->count_error);
                }
             
          }
    
    function checkMemcache() {
        try {
            if(!class_exists('Memcache')){
            $this->count_error++;
            throw new MException('Memcache не установлен <br>');    
            }
            echo 'Memcache установлен';      
        }
        catch (MException $e) {
                $e->logErrors($this->count_error);
        }
        
              
    } 
    function checkRedis(){
        try {
            if(!class_exists('Redis')) {
                
                $this->count_error++;
                throw new Exception('Redis не установлен!<br>');
            }
            echo 'Redis установлен<br>';
        } 
        catch (Exception $e) {
         echo $e->getMessage();
        }
    }
}*/