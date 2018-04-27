<?php
/**
 * Created by PhpStorm.
 * User: mamedov
 * Date: 02.04.2018
 * Time: 20:38
 */
namespace ModuleAuth;
class UserSession
{
    const COOKIE_KEY = "rddsfgaed";
    private static $inst=null;
    public static function instance(){
        return self::$inst===null
            ?self::$inst=new self()
            :self::$inst;
    }
    private $time,$long_time;

    private function __construct(){
        $config = \Config::load("user_session");
        $this->time = $config->time;
        $this->long_time = $config->longtime;
    }

    private static function getIp(){
        return md5($_SERVER["REMOTE_ADDR"]);
    }
    private static function getAgent(){
        return md5($_SERVER["HTTP_USER_AGENT"]);
    }
    private static function getToken($id){
        $data = $id.self::getAgent().self::getIp().time();
        return \ModuleHash::getPassHasher()->passHash($data);
    }


    public function createSession($id,$long=false){
        $time = $long ? $this->long_time : $this->time;
        $token = self::getToken($id);
        \ModuleDatabase::instance()->user_tokens->insert([
            'user_agent'=>self::getAgent(),
            'user_ip'=>self::getIp(),
            'user_id'=>$id,
            'token'=>$token,
            'expires'=>$time+time(),
            'created'=>time()
        ]);
        setcookie(self::COOKIE_KEY,$token,$time+time(),URLROOT);
    }
    private $session = null;
    public function validateSession():bool {
        if($this->session!==null) return !empty($this->session);
        if(empty($_COOKIE[self::COOKIE_KEY]))return false;
        $this->session = \ModuleDatabase::instance()
            ->user_tokens
            ->where("token",$_COOKIE[self::COOKIE_KEY])
            ->first();
        if(empty($this->session)) return false;
        if($this->session["user_ip"]!=self::getIp()) return false;
        if($this->session["user_agent"]!=self::getAgent()) return false;
        //TODO:time session
        return true;
    }
    public function getUserIdFromSession(){
        if(empty($this->session)) throw new \Exception();
        return (int)$this->session["user_id"];
    }
    public function destroySession($deep=false){
        if(!$this->validateSession()) return;
        if(!$deep){
            \ModuleDatabase::instance()->user_tokens->delete($this->session["id"]);
            setcookie(self::COOKIE_KEY,"",time()-1,URLROOT);
        }else{
            \ModuleDatabase::instance()->user_tokens->deleteWhere("user_id=?",[(int)$this->session["user_id"]]);
            setcookie(self::COOKIE_KEY,"",time()-1,URLROOT);
        }
    }
    public function continueSession(){

    }
}