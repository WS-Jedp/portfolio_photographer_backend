<?php

namespace App\Http;

class Response {
  protected $data;
  protected $type;

  public function __construct($type, $data)
  {
    $this->data = $data;
    $this->type = $type;
  }


  private function setResponse($type, $data)
  {
    switch ($type) {
      case "json":
        header('Content-Type: application/json');
        echo $data;
        break;

      case "json":
        echo $data;
        break;
      
      default:
        return "Sorry, bad type of resposne";
    }
  }
  

  public function send()
  {
    echo $this->setResponse($this->type, $this->data);
  }
}