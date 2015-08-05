<?php
class FilesException Extends Exception {

    function logErrors($errors) {
        echo parent::getMessage();
    }
}
class checkFiles {
    const TEST_FAILED = 0;
    const TEST_PASSED = 1;
    public static $answersCode = array(
        self::TEST_FAILED => 'Тест записи в файл не пройден',
        self::TEST_PASSED => 'Тест записи в файл пройден'
    );
    public $answer = '';
            function checkFiles() {
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..';
        try {
            if(file_put_contents($path.'/test.txt', 'test_content')===FALSE){
                throw new FilesException($this->getCode(self::TEST_FAILED));
                }
            $this->getCode(self::TEST_PASSED);
            unlink($path.'/test.txt');
        }
        catch (FilesException $e) {
            
                } 
        }
    function getCode($CodeAnswer) {
        $this->answer = $CodeAnswer;
    }
    function run() {
        $this->checkFiles();
        return self::$answersCode[$this->answer];
    }
}

