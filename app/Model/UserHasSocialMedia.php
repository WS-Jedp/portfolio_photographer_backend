<?php

namespace Models;

use App\Database\MySql;

class UserHasSocialMedia {

  protected $table = 'user_has_social_media';
  protected $columns = ['id', 'user_id', 'social_media_id'];
  protected $innerTables = ['user' => 'user_id', 'social_media' => 'social_media_id'];
  protected $db;

  public function __construct()
  {
    $this->db = new MySql();
  }

  /**
   * Will return as an associative array of the Social Medias from the user
   * 
   * @param int $id The id of the User which  we want know the Social Media that have
   * 
   * @return array The data of the query
   */
  public function getSocialMedia($id)
  {
    $columns = [
      'user' => ['id as user_id', 'name as user_name', 'email as user_email'],
      'social_media' => ['name as social_media_name', 'url as social_media_url']
    ];
    $on = [
      'social_media' => 'id',
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