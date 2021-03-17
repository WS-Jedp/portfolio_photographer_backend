<?php

namespace App\Http\Controllers;

use App\Http\Response;
use Helpers\ErrorReport;

use Helpers\TokenJWT;

use Models\AlbumHasPhotos;
use Models\CameraHasLen;
use Models\FeaturedAlbum;
use Models\FeaturedPhotos;


class ApiController {

  protected $albumHasPhotos;
  protected $cameraHasLen;
  protected $featuredAlbum;
  protected $featuredPhotos;
  protected $auth = false;

  public function __construct()
  {
    $this->albumHasPhotos = new AlbumHasPhotos();
    $this->cameraHasLen = new CameraHasLen();
    $this->featuredAlbum = new FeaturedAlbum();
    $this->featuredPhotos = new FeaturedPhotos();
    return $this->verify();
  }
  
  private function verify()
  {
    $err = new ErrorReport("Unathorized! You must need a valid token!");
    if(empty($_COOKIE["token"])) {
      $this->auth = false;
      return $err->unauthorized();
    }
    $jwt = new TokenJWT();
    $is_valid = $jwt->verify($_COOKIE["token"]);
    
    if($is_valid) {
      $this->auth = true;
    } else {
      return $err->unauthorized();
    }
  }

  public function Cameras()
  {
    if(!$this->auth) {
      return $this->verify();
    }
    try {      
      $camerasAndLenses = $this->cameraHasLen->getLenses(1);
      $json = [
        "status" => 200,
        "message" => "The cameras with his lenses were fetched succesfully",
        "data" => [
          "cameras" => $camerasAndLenses
        ]
      ];

      return new Response("json", json_encode($json));
      

    } catch(\Exception $exception) {
      $err = new ErrorReport($exception->getMessage());
      return $err->normal();
    }
  }

  public function FeaturedAlbum()
  {

    try {   
       
      $albums = $this->featuredAlbum->getAlbums();
      $json = [
        "status" => 200,
        "message" => "The featured Albums were fetched succesfully",
        "data" => [
          "albums" => $albums
        ]
      ];

      return new Response("json", json_encode($json));
      

    } catch(\Exception $exception) {
      $err = new ErrorReport($exception->getMessage());
      return $err->normal();
    }
  }
  
  public function FeaturedPhotos()
  {
    try {
      
      $photos = $this->featuredPhotos->getPhotos();
      $json = [
        "status" => 200,
        "message" => "The featured photos were fetched succesfully",
        "data" => [
          "photos" => $photos
        ]
      ];

      return new Response("json", json_encode($json));
      

    } catch(\Exception $exception) {
      $err = new ErrorReport($exception->getMessage());
      return $err->normal();
    }
  }
}