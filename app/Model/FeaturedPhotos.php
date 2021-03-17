<?php

namespace Models;

use App\Database\MySql;

class FeaturedPhotos {

  protected $db;

  protected $table = 'featured_photo';
  protected $innerTable = 'photo';
  protected $columns = ['id', 'photo_id'];

  public function __construct()
  {
    $this->db = new MySql();
  }

  public function getPhotos() 
  {
    try {

      $columns = [
        "main" => $this->columns,
        "inner" => ['name as phto_name', 'date as photo_date', 'description as photo_description', 'location as photo_location']
      ];

      $on = [
        "main" => "photo_id",
        "inner" => "id"
      ];

      $data = $this->db->innerJoin($this->table, $this->innerTable, $columns, $on);

      return $data;

    } catch(\Exception $exception) {
      throw $exception;
    }

  }
}