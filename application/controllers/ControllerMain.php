<?php
class ControllerMain extends Controller
{
    public function action_index(){
        $view = new View("main");
        $view->useTemplate();
        $view->title="Welcome page";
        $this->response($view);
    }
}