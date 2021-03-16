<?php

namespace Helpers;
use App\Http\Response;

class ErrorReport {
  protected $type = "json";
  protected $message;

  public function __construct($message)
  {
    $this->message = $message;
  }

  public function normal()
  {
    $json = [
      "message" => $this->message,
      "status" => 500
    ];
    http_response_code(500);
    return new Response($this->type, json_encode($json));
  }

  public function database()
  {
    $json = [
      "message" => $this->message,
      "status" => 500,
      "where" => "database"
    ];
    http_response_code(500);
    return new Response($this->type, json_encode($json));
  }

  public function badRequest()
  {
    $json = [
      "message" => $this->message,
      "status" => 400,
    ];
    http_response_code(501);
    return new Response($this->type, json_encode($json));
  }

  public function unauthorized()
  {
    $json = [
      "message" => $this->message,
      "status" => 401
    ];

    http_response_code(401);
    return new Response($this->type, json_encode($json));
  }
}