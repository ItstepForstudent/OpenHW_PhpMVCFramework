<?php
namespace ModuleValidator;
abstract class Rule
{
    abstract function test(string $str):bool;
    private $name;
    const PATTERN = "/\{.+}\/i";
    protected $params=[];



    public function __construct($pattern){
        $this->pattern=$pattern; $validator = new Validator([
            "name" => "min{4}|max{10}|login",
            "password" => "min{8}|max{16}|pass|rexp{/fdsfsdf/i}"
        ]);
        try {
            $validator->validate($_POST);
        }catch (ValidatorException $ex){

        }

    }
}