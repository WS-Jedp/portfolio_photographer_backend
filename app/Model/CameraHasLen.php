<?php

namespace Models;

use App\Database\MySql;

class CameraHasLen {

  protected $table = 'camera_has_len';
  protected $columns = ['id', 'camera_id', 'len_id'];
  protected $innerTables = ['camera' => 'camera_id', 'len' => 'len_id'];
  protected $db;

  public function __construct()
  {
    $this->db = new MySql();
  }

  /**
   * Will return as an associative array of the Lenses from the camera
   * 
   * @param int $id The id of the Camera that we want the lenses
   * 
   * @return array The data of the query
   */
  public function getLenses($id)
  {
    $columns = [
      'camera' => ['id as camera_id', 'brand as camera_brand', 'model as camera_model'],
      'len' => ['id as len_id', 'mm as len_mm']
    ];
    $on = [
      'camera' => 'id',
      'len' => 'id'
    ];
    $condition = [
      "camera_id" => $id
    ];

    try {
      $data =$this->db->multipleInnerJoin($this->table, $this->innerTables, $columns, $on, $condition);
      return $data;
    } catch(\Exception $exception) {
      throw $exception;
    }
  }

}