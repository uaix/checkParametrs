<?php
class SessionException Extends Exception {
     
     function logErrors($errors) {
        echo parent::getMessage().'<br>Количество ошибок: '.$errors.'<br>';
    }
         
 }
 class checkSession {
     const sessionFailed = 0;
     const sessionSetup = 1;
     const sessionSetuped = 2;
     public static $answersCode = array(
         self::sessionFailed => 'Сессию не удалось создать',
         self::sessionSetup => 'Сессия создалась успешно',
         self::sessionSetuped => 'Сессия уже существует'
     );
    public $answer = array();
    public $text_answer = array();
            function __construct(){
		session_start();
	}
    function checkSession() {
			if(!$_SESSION['flag']){
				try{
					if(!($_SESSION['flag'] = "1")){
						throw new SessionException($this->getCode(self::sessionFailed));
					}
					header( "refresh:1;url=".$_SERVER['PHP_SELF']."");
				}
				catch (SessionException $e){
                                }
			}else{
				$this->getCode(self::sessionSetuped);
			}
        }
    function getCode($CodeAnswer) {
        $this->answer[] = $CodeAnswer;
    }
    function run() {
		foreach ($this->answer as $value) {
                $this->text_answer[] = self::$answersCode[$value];            
            }
        return $this->text_answer;
    }
}