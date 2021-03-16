<?php

namespace App\Http;

class Request {

  protected $controller;
  protected $method;
  protected $parameter;

  public function __construct()
  {
    $segments = explode("/", $_SERVER["REQUEST_URI"]);
    
    $this->controller = empty($segments[1]) ? "Home" : $segments[1];
    $this->method = empty($segments[2]) ? 'Index' : $segments[2];
    $this->parameter = empty($segments[3]) ? NULL : $segments[3];

    $this->send();
  }

  protected function getAppController() {
    $controller = ucfirst($this->controller);
    return "App\Http\Controllers\\{$controller}Controller";
  }

  protected function getAppMethod() {
    $method = $this->method;
    return ucfirst($method);
  }

  public function send() {
    $controller = $this->getAppController();
    $method = $this->getAppMethod();
    $parameter = $this->parameter;
    
    $response = call_user_func([
      new $controller,
      $method
    ], $parameter);

    $response->send();
  } 
}
