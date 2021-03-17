<?php

namespace Models;

use App\Database\MySql;

class AlbumHasPhotos {

  protected $table = 'album_has_photo';
  protected $columns = ['id', 'album_id', 'photo_id'];
  protected $innerTables = ['photo', 'album'];
  protected $db;

  public function __construct()
  {
    $this->db = new MySql();
  }

  /**
   * Will return as an associative array the Photos of an album
   * 
   * @param int $id The id of the Album that we want the photos
   * 
   * @return array The data of the query
   */
  public function getPhotos($id)
  {

    $innerTables = ['album' => 'album_id', 'photo' => 'photo_id'];
    $columns = [
      'album' => ['id', 'name as album_name', 'concept as album_concept'],
      'photo' => ['id', 'name as photo_name']
    ];
    $on = [
      'album' => 'id',
      'photo' => 'id'
    ];
    $condition = [
      "id" => $id
    ];

    try {
      $data =$this->db->multipleInnerJoin($this->table, $innerTables, $columns, $on, $condition);
      return $data;
    } catch(\Exception $exception) {
      throw $exception;
    }
  }

}