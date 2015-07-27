<?php
class RException Extends Exception {

    function logErrors($errors) {
        parent::getMessage();
    }
}
class checkRedis{
    const REDIS_NOT_AVIALABLE = 0;
    const REDIS_AVIALABLE = 1;
    public static $answersCode = array(
        self::REDIS_NOT_AVIALABLE => 'Redis не установлен',
        self::REDIS_AVIALABLE => 'Redis установлен'
    );
    public $answer = '';
            function checkRedis(){
        try {
            if(!class_exists('Redis')) {
                throw new RException($this->getCode(self::REDIS_NOT_AVIALABLE));
            }
            $this->getCode(self::REDIS_AVIALABLE);
        } 
        catch (RException $e) {
        }
    }
    function getCode($CodeAnswer) {
        $this->answer = $CodeAnswer;
    }
    function run() {
        $this->checkRedis();
        return self::$answersCode[$this->answer];
    }
}
