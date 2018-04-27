<?php

class ModuleDebug{
    private static function _dump($array){
        echo "<div style='background: black;padding: 20px;'>";
        foreach ($array as $key=>$value){

            if(!is_array($value)){

                echo "<div><span style='color:red'>{$key}</span>=> <span style='color:wheat;'>{$value}</span> (".gettype($value).")</div>";

            }else{
                echo "<details style='color: green'>";
                echo "<summary><span style='color:red'>{$key} </span></summary>";
                self::_dump($value);
                echo "</details>";
            }

        }
        echo "</div>";
    }
    private static function baseDump($value){
        if(is_array($value)) self::_dump($value);
        else{
            echo "<pre>";
            var_dump($value);
        }
    }

    public static function dump($value){
        echo "<style>body{padding-top: 50vh !important;}</style>";
        echo "<div style='position: fixed;z-index:10000000;top:0;left:0;right:0;height: 50vh; overflow-y: scroll;'>";
        self::baseDump($value);
        echo "</div>";
    }
    public static function dd($value){
        self::baseDump($value);
        die();
    }
}