<?php


abstract class Controller{
    protected function response($response){
        echo $response;
    }
    protected function getUriParam($name){
        return Router::instance()->getParam($name);
    }
    protected function redirect($uri){
        Router::instance()->redirect($uri);
    }
    protected function redirect404(){
        Router::instance()->redirect404();
    }
}