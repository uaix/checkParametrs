<?php
class SessionException Extends Exception {
     
     function logErrors($errors) {
        echo parent::getMessage().'<br>Количество ошибок: '.$errors.'<br>';
    }
         
 }
 class CheckSession {
     const sessionFailed = 0;
     const sessionSetup = 1;
     const sessionSetuped = 2;
     public static $answersCode = array(
         self::sessionFailed => 'Сессию не удалось создать',
         self::sessionSetup => 'Сессия создалась успешно',
         self::sessionSetuped => 'Сессия уже существует'
     );
    public $answer = ''; 
    function checkSession() {
         if(!isset($_SESSION['test']))
            {
            try{
                if($_SESSION['test']!='test_session') {
                    throw new SessionException($this->getCode(self::sessionFailed));
                  }
                $_SESSION['test'] = 'test_session';
                $this->getCode(self::sessionSetup);
            } 
            catch (SessionException $e){
                }
            }
            else {
                $this->getCode(self::sessionSetuped);
            }
        }
    function getCode($CodeAnswer) {
        $this->answer = $CodeAnswer;
    }
    function run() {
        $this->checkSession();
        return self::$answersCode[$this->answer];
    }
}