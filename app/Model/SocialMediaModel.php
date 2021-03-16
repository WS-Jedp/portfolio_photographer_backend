<?php

namespace Models;

use App\Database\MySql;

class SocialMediaModel {
  protected $db;
  protected $table = "social_media";
  protected $columns = [
    "id",
    "name",
    "url"
  ];

  public function __construct()
  {
    $this->db = new MySql(); 
  }

  
  /**
   * Will return One Social Media, the one according to the ID passed by his param
   * 
   * @param integer id -> Must be the id of the element that we want to find
   *  
   */
  public function getOne($id)
  {
    try {
      $social_media = $this->db->getOne($this->table, $this->columns, $id);

      return $social_media;
    } catch(\Exception $exception) {
      throw $exception;
    }
  }


  /**
   * Get all the Social Medias of our database
   * 
   * @return array Will return an array with the whole data of our table database
   */
  public function getAll()
  {
    try {
      $social_medias = $this->db->getAll($this->table, $this->columns);
      return $social_medias;
    } catch(\Exception $exception) {
      throw $exception;
    }
  }

  /**
   * Will receive the data that we'll create into the Social Media table
   * 
   * @param array $data {
   *    @type associative array
   *    Key => value
   * }
   * 
   * @return int $id Return the last id of the Social Media created
   *  
   */
  public function createOne($data)
  {
    try {

      $columns = [];
      $values = [];

      foreach ($data as $key => $value) {
        array_push($columns, $key);
        array_push($values, $value);
      }

      $id = $this->db->createOne($this->table, $columns, $values);
      return $id;
    } catch (\Exception $exception) {
      throw $exception;
    }
  }


  /**
   * Will delete the Social Media defined with the Id that was passed by the parameters
   * 
   * @param int $id The id of the Social Media to delete
   * 
   * @return int $id Return the Id of the Social Media deleted 
   */
  public function deleteOne($id) {
    try {
      $id = $this->db->deleteOne($this->table, $id);
      return $id;
    } catch (\Exception $exception) {
      throw $exception;
    }
  }
}