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
      "status" => 401
    ];
    return new Response(json_encode($json), $this->type);
  }

  public function database()
  {
    $json = [
      "message" => $this->message,
      "status" => 404,
      "where" => "database"
    ];
    return new Response(json_encode($json), $this->type);
  }

  public function badRequest()
  {
    $json = [
      "message" => $this->message,
      "status" => 501,
    ];
    http_response_code(501);
    return new Response(json_encode($json), $this->type);
  }

  public function unauthorized()
  {
    $json = [
      "message" => $this->message,
      "status" => 401
    ];

    http_response_code(401);
    return new Response(json_encode($json), "json");
  }
}