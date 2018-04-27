<?php
class ModuleAuth
{
    private $hasher,$db;
    private static $inst=null;
    public static function instance(){
        return self::$inst===null
            ?self::$inst=new self()
            :self::$inst;
    }
    private function __construct(){
        $this->hasher = ModuleHash::getPassHasher();
        $this->db = ModuleDatabase::instance();
    }
    public function register($login,$pass,$data=[]):int{
        if($this->db->users->countOf("login=?",[$login])>0) throw new Exception("user exists");
        $data["login"]=$login;
        $data["pass"]=$this->hasher->passHash($pass);
        return $this->db->users->insert($data);
    }
    public function login($login,$pass){
        $user = $this->db->users->where("login",$login)->first();
        if(!$user) throw new Exception("login invalid");
        if(!$this->hasher->comparePass($pass,$user["pass"]))
            throw  new Exception("pass invalid");
        //TODO:session_start

    }



}