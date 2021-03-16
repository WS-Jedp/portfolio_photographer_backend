<?php 

namespace App\Http\Controllers;

use Helpers\ErrorReport;
use App\Http\Response;

class HomeController {
  public function Index() {

    $json_data = [
      "msg" => "Hello world",
      "status" => 200,
    ];
    http_response_code(200);
    header('Content-Type: application/json');

    return new Response('json', json_encode($json_data));
  }
}