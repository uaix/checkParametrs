<?php
class MCException Extends Exception {

    function logErrors($errors) {
        parent::getMessage();
    }
}

class checkMemcache {
    const MEMCACHE_NOT_FOUND = 0;
    const MEMCACHE_FOUND = 1;
    const MEMCACHE_NO_CONNECT = 2;
    const MEMCACHE_CONNECT = 3;
    public $host = 'localhost';
    public $port = 11211;
    public static $answersCode = array(
        self::MEMCACHE_NOT_FOUND => 'Memcache не найден',
        self::MEMCACHE_FOUND => 'Memcache найден',
        self::MEMCACHE_NO_CONNECT => 'Memcache не подключен',
        self::MEMCACHE_CONNECT => 'Memcache подключен',
    );
    public $answer = array();
    
    function checkMemcacheAvailable() {
        try {
            if(!class_exists('Memcache')){
            throw new MCException($this->getCode(self::MEMCACHE_NOT_FOUND));    
                }
            $this->getCode(self::MEMCACHE_FOUND);
            $this->checkMemcacheConnect();

        }
        catch (MCException $e) {
        }
    }
    function checkMemcacheConnect() {
        try{
            $memcache = new Memcache;
            if (!$memcache->connect($this->host, $this->port)){
            throw new MCException($this->getCode(self::MEMCACHE_NO_CONNECT));  
            }
            $this->getCode(self::MEMCACHE_CONNECT);
        }
        catch (MCException $e) {
        }
    }
    function getCode($CodeAnswer) {
        $this->answer[] = $CodeAnswer;
    }
    function run() {
        $this->checkMemcacheAvailable();
        $text_answer = array();
        foreach ($this->answer as $value) {
            $text_answer[] .= self::$answersCode[$value];
        }
        return $text_answer;
    }
}
