<?php

namespace Models;

use App\Database\MySql;

class LenModel {
  protected $db;
  protected $table = "len";
  protected $columns = [
    "mm",
    "iso",
    "aperture",
    "shutter_speed",
  ];

  public function __construct()
  {
    $this->db = new MySql(); 
  }

  
  /**
   * Will return One Len, the one according to the ID passed by his param
   * 
   * @param integer id -> Must be the id of the element that we want to find
   *  
   */
  public function getOne($id)
  {
    try {
      $len = $this->db->getOne($this->table, $this->columns, $id);

      return $len;
    } catch(\Exception $exception) {
      throw $exception;
    }
  }


  /**
   * Get all the Lenses of our database
   * 
   * @return array Will return an array with the whole data of our table database
   */
  public function getAll()
  {
    try {
      $lenses = $this->db->getAll($this->table, $this->columns);
      return $lenses;
    } catch(\Exception $exception) {
      throw $exception;
    }
  }

  /**
   * Will receive the data that we'll create into the Len table
   * 
   * @param array $data {
   *    @type associative array
   *    Key => value
   * }
   * 
   * @return int $id Return the last id of the Len created
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
   * Will delete the Len defined with the Id that was passed by the parameters
   * 
   * @param int $id The id of the len to delete
   * 
   * @return int $id Return the Id of the len deleted 
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