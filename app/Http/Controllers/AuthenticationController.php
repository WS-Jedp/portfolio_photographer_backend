<?php 

namespace App\Http\Controllers;

use App\Http\Response;
use DateTime;
use Helpers\ErrorReport;
use Helpers\TokenJWT;
use Models\UserModel;


/**
 * Will help us to Authenticate our user
 * Will give us the methods:
 * - Login
 * - Verify
 */
class AuthenticationController {

  private $userModel;

  public function __construct()
  {
    $this->userModel = new UserModel();
    header("Access-Control-Allow-Origin: http://localhost:8000");
    header("Access-Control-Allow-Methods: GET | POST");
  }


  /**
   * Must be passed by form-data in POST request method the email and password of the user
   * 
   * @return Response Will return a Response with the Token of the user 
   */
  public function Login()
  {
    if($_SERVER["REQUEST_METHOD"] === "POST") {

      try {

        $require_data = ["email", "password"];
        
        for ($i=0; $i < count($require_data); $i++) { 
          $in_array = array_key_exists($require_data[$i], $_POST);
          if(!$in_array) {
            $err = new ErrorReport("Sorry, Cad credentials");
            return $err->normal();
          }
        }
        
        $user = $this->userModel->Login($_POST["email"], $_POST["password"]);
        
        if($user) {
          $now = (new DateTime("now"))->getTimestamp();
          $JWT = new TokenJWT();
          $payload = [
            "id" => $user["id"],
            "email" => $user["email"],
            "exp" => $now + 500
          ];
          $token = $JWT->create($payload);

          $json = [
            "status" => 200,
            "message" => "User Authenticated!",
            "data" => [
              "user" => $user,
              "token" => $token
            ]
          ];

          setcookie("token", $token, [
            "httponly" => false,
            "secure" => false
          ]);

          return new Response("json", json_encode($json));
        }

      } catch(\Exception $exception) {
        $err = new ErrorReport($exception->getMessage());
        return $err->normal();
      }

    }

    $err = new ErrorReport("Bad request method");
    return $err->badRequest();
  }
  
}