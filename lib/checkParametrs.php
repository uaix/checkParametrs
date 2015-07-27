<?php
class BDException Extends Exception {

    function logErrors($errors) {
        echo parent::getMessage().'<br>Количество ошибок: '.$errors.'<br>';
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
        
        $table_name = 'MyGuests';//name of table where we do our tests
        
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
    
}