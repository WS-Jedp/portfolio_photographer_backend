<?php

namespace App\Http\Controllers;

use App\Http\Response;
use \Models\PhotoModel;
use Helpers\ErrorReport;
use \Exception;

class PhotoController {

  private $photoModel;

  public function __construct()
  {
    $this->photoModel = new PhotoModel();    
  }
  /**
   * Will return all the Photos saved into our database
   * 
   * @return Response Will return a new Resonse with the according data of the response  
   */
  public function Index() {
    try {

      $photos = $this->photoModel->getAll();
      $json = [
        "status" => 200,
        "message" => "The photos were succesfully fetched",
        "data" => [
          "photos" => $photos
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
   * Will return one photo of our database
   * 
   * @param int $id The id of the photo that we want to find
   * 
   * @return Response Will return the JSON
   */
  public function find($id) {
  try {
    $photo = $this->photoModel->getOne($id);

    $json = [
      "status" => 200,
      "data" => [
        "photo" => json_decode($photo)
      ]
    ];

    return new Response('json', json_encode($json));

  } catch(\Exception $exception) {
    $err = new ErrorReport($exception->getMessage());
    return $err->normal();
  }
 }


 /**
  * Will create one Photo into the table Photo in our database
  * @return Response Will return a Resonse according to the type of Response with the data of the action. 
  */
  public function create() {
    if($_SERVER["REQUEST_METHOD"] === "POST") {

      $require_data = ["name","location", "camera_id", "len_id", "user_id"]; 

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

      $id = $this->photoModel->createOne($data);

      $json = [
        "status" => 201,
        "message" => "The $id photo was created succesfully",
        "data" => [
          "id" => $id
        ]
      ];
      http_response_code(201); 

      return new Response('json', json_encode($json));

    } else {
      $err = new ErrorReport("Bad method of request");
      return $err->badRequest();
    }
  }

  /**
   * WIll delete the id's photo passed by the UR.
   * 
   * @return int $id Will return the Id of the photo deleted 
   */
  public function delete()
  {
    if($_SERVER["REQUEST_METHOD"] === "POST") {

      try {
        $required_data = ["id"];

        for ($i=0; $i < count($required_data); $i++) { 
          $in_array = array_key_exists($required_data[$i], $_POST);
          if(!$in_array) {
            $err = new ErrorReport("The data is incomplete, please define the ID of the photo");
            return $err->normal;
          }
        }

        $id = $this->photoModel->deleteOne($_POST["id"]);

        $json = [
          "status" => 201,
          "message" => "The photo with the $id was deleted succesfully",
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