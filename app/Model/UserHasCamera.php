<?php

namespace Models;

use App\Database\MySql;

class UserHasCamera {

  protected $table = 'user_has_camera';
  protected $columns = ['id', 'user_id', 'camera_id'];
  protected $innerTables = ['user' => 'user_id', 'camera' => 'camera_id'];
  protected $db;

  public function __construct()
  {
    $this->db = new MySql();
  }

  /**
   * Will return as an associative array of the Cameras from the user
   * 
   * @param int $id The id of the User which  we want know the Cameras that have
   * 
   * @return array The data of the query
   */
  public function getCameras($id)
  {
    $columns = [
      'user' => ['id as user_id', 'name as user_name', 'email as user_email'],
      'camera' => ['id as camera_id', 'model as camera_model', 'brand as camera_brand']
    ];
    $on = [
      'camera' => 'id',
      'user' => 'id'
    ];
    $condition = [
      "user_id" => $id
    ];

    try {
      $data =$this->db->multipleInnerJoin($this->table, $this->innerTables, $columns, $on, $condition);
      return $data;
    } catch(\Exception $exception) {
      throw $exception;
    }
  }

}