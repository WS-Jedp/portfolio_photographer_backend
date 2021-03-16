<?php

namespace Models;

use App\Database\MySql;

/**
 * PhotoModel with act as a ORM for the table Photo in our database 
 *** Will give the next methods:
 * - getOne
 * - getAll
 * - getBy
 */
class PhotoModel {

  private $db;
  private $table = "photo";
  private $columns = [
    "id",
    "name",
    "description",
    "location",
    "date",
    "camera_id",
    "len_id",
    "user_id"
  ];
  
  public function __construct()
  {
    $this->db = new MySql();
  }
  

  /**
   * Will return One photo, the one according to the ID passed by his param
   * 
   * @param integer id -> Must be the id of the element that we want to find
   *  
   */
  public function getOne($id)
  {
    try {
      $photo = $this->db->getOne($this->table, $this->columns, $id);

      return $photo;
    } catch(\Exception $exception) {
      throw $exception;
    }
  }


  /**
   * Get all the photos of our database
   * 
   * @return array Will return an array with the whole data of our table database
   */
  public function getAll()
  {
    try {
      $photos = $this->db->getAll($this->table, $this->column);
      return $photos;
    } catch(\Exception $exception) {
      throw $exception;
    }
  }

  /**
   * Will receive the data that we'll create into the Photo table
   * 
   * @param array $data {
   *    @type associative array
   *    Key => value
   * }
   * 
   * @return int $id Return the last id of the Photo created
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
   * Will delete the Photo defined with the Id that was passed by the parameters
   * 
   * @param int $id The id of the photo to delete
   * 
   * @return int $id Return the Id of the photo deleted 
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