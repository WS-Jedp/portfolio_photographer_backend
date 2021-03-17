<?php

namespace Models;

use App\Database\MySql;

class FeaturedAlbum {

  protected $db;

  protected $table = 'featured_album';
  protected $innerTable = 'album';
  protected $columns = ['id', 'album_id'];

  public function __construct()
  {
    $this->db = new MySql();
  }

  public function getAlbums() 
  {
    try {

      $columns = [
        "main" => $this->columns,
        "inner" => ['name as album_name', 'date as album_date', 'concept as album_concept', 'description as album_description']
      ];

      $on = [
        "main" => "album_id",
        "inner" => "id"
      ];

      $data = $this->db->innerJoin($this->table, $this->innerTable, $columns, $on);

      return $data;

    } catch(\Exception $exception) {
      throw $exception;
    }

  }
}