<?php
include MODULES_PATH."ModuleDatabase/Executor.php";

use ModuleDatabase\Executor as ModuleDatabaseExecutor;
class ModuleDatabase{
    private static $inst=NULL;
    public static function instance(){
        return self::$inst
            ? self::$inst
            : self::$inst=new self();
    }

    private $dbh;
    private $tables;
    private function __construct(){
        $config = Config::load("database");
        $connecting = "mysql:dbname={$config->dbname};host={$config->host};charset={$config->charset}";
        $this->dbh = new PDO($connecting,$config->login,$config->pass,[
           PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
           PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION
        ]);
        $this->tables = $this->dbh->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * @param $name
     * @throws Exception
     */
    public function __get($name){
        if(!in_array($name,$this->tables)) throw new Exception("Table not exists");
        return new ModuleDatabaseExecutor($this->dbh,$name);
    }
}



