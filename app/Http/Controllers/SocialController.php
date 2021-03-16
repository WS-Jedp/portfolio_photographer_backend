<?php

namespace App\Http\Controllers;

use App\Http\Response;
use Helpers\ErrorReport;
use Models\SocialMediaModel;

class SocialController {
  private $socialMediaController;

  public function __construct()
  {
    $this->socialMediaController = new SocialMediaModel();    
  }
  /**
   * Will return all the Social Meidas saved into our database
   * 
   * @return Response Will return a new Response with the according data of the response  
   */
  public function Index() {
    try {

      $socialMedias = $this->socialMediaController->getAll();
      $json = [
        "status" => 200,
        "message" => "The Social Medias were succesfully fetched",
        "data" => [
          "socialMedias" => $socialMedias
        ]
      ];
      http_response_code(200);

      return new Response("json", json_encode($json));

    } catch(\Exception $exception) {
      $err = new ErrorReport($exception->getMessage());
      return $err->normal();
    }
  }


  /**
   * Will return one Camera  of our database
   * 
   * @param int $id The id of the Social Media that we want to find
   * 
   * @return Response Will return the JSON
   */
  public function find($id) {
  try {
    $socialMedia = $this->socialMediaController->getOne($id);

    $json = [
      "status" => 200,
      "data" => [
        "socialMedia" => $socialMedia
      ]
    ];

    return new Response('json', json_encode($json));

  } catch(\Exception $exception) {
    $err = new ErrorReport($exception->getMessage());
    return $err->normal();
  }
 }


 /**
  * Will create one Social Media into the table Photo in our database
  * @return Response Will return a Resonse according to the type of Response with the data of the action. 
  */
  public function create() {
    if($_SERVER["REQUEST_METHOD"] === "POST") {

      try {
        $require_data = ["name", "url"]; 

        for($i = 0; $i < count($require_data); $i++) {
          $in_array = array_key_exists($require_data[$i], $_POST);
          if(!$in_array) {
            $err = new ErrorReport("The field {$require_data[$i]} left, please submit all the required data");
            return $err->normal();
          }
        }

        $data = [];

        foreach ($_POST as $key => $value) {
          $data[$key] = $value;
        }

        $id = $this->socialMediaController->createOne($data);
        
        $json = [
          "status" => 201,
          "message" => "The $id Social Media was created succesfully",
          "data" => [
            "id" => $id
          ]
        ];
        http_response_code(201); 

        return new Response("json", json_encode($json));

      } catch(\Exception $exception) {
        $err = new ErrorReport($exception->getMessage());
        return $err->database();
      }

      

    } else {
      $err = new ErrorReport("Bad method of request");
      return $err->badRequest();
    }
  }

  /**
   * WIll delete the id's Social Media passed by the URL.
   * 
   * @return int $id Will return the Id of the Social Media deleted 
   */
  public function delete()
  {
    if($_SERVER["REQUEST_METHOD"] === "POST") {

      try {
        $required_data = ["id"];

        for ($i=0; $i < count($required_data); $i++) { 
          $in_array = array_key_exists($required_data[$i], $_POST);
          if(!$in_array) {
            $err = new ErrorReport("The data is incomplete, please define the ID of the Social Media");
            return $err->normal;
          }
        }

        $id = $this->socialMediaController->deleteOne($_POST["id"]);

        $json = [
          "status" => 201,
          "message" => "The Social Media with the $id was deleted succesfully",
          "data" => [
            "id" => $id
          ]
        ];

        return new Response("json", json_encode($json));
      } catch (\Exception $exception) {
        $err = new ErrorReport($exception->getMessage());
        return $err->database();
      }

      

    } else {
      $err = new ErrorReport("Bad type of request");
      return $err->badRequest();
    }
  }


}