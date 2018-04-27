<?php
    defined("DOCROOT") or die("NO DIRECT ACCESS");
    include CLASS_PATH."config.php";
    include CLASS_PATH."Router.php";
    include CLASS_PATH."Controller.php";
    include CLASS_PATH."View.php";
    include CLASS_PATH."Model.php";
    include CLASS_PATH."Entity.php";
    include CLASS_PATH."Autoloader.php";

    spl_autoload_register("Autoloader::load");

    $router = Router::instance();
    $router->addRoute(new Route("",[
        "controller"=>"main",
        "action"=>"index"
    ]));
    $router->addRoute(new Route("cats/{id}",[
        "controller"=>"main",
        "action"=>"details"
    ]));
    $router->addRoute(new Route("addcat",[
        "controller"=>"main",
        "action"=>"addcat"
    ]));



    try{
        $router->run();
    }catch (RouterException $e){
        $router->redirect404();
        //echo $e->getMessage();
    }


