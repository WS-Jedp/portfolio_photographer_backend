<?php

namespace Models;

use App\Database\MySql;
use Exception;

class UserModel {
  protected $db;
  protected $table = "user";
  protected $columns = [
    "name",
    "email",
    "phone",
    "description",
    "location",
  ];

  public function __construct()
  {
    $this->db = new MySql(); 
  }

  
  /**
   * Will authenticate the user in our database
   * 
   * @param string $email The email which is the user registered
   * @param string $password The password which is the user registerd
   * 
   * @return array Will return an Array with the information of the User 
   */
  public function Login($email, $password)
  {
    try {

      $columns = ["id", "email", "password"];
      $condition = ["email" => $email];

      $user = $this->db->getLogin($this->table, $columns, $condition);

      $auth_password = password_verify($password, $user[0]["password"]);

      if($auth_password) {

        $user_data = [
          "id" => $user[0]["id"],
          "email" => $user[0]["email"]
        ];
        return $user_data;

      } else {
        throw new Exception("Bad credentials!");
      }
      


    } catch(\Exception $exception) {
      throw $exception;
    }
  }

  
  /**
   * Will return One User, the one according to the ID passed by his param
   * 
   * @param integer id -> Must be the id of the element that we want to find
   *  
   */
  public function getOne($id)
  {
    try {
      $user = $this->db->getOne($this->table, $this->columns, $id);

      return $user;
    } catch(\Exception $exception) {
      throw $exception;
    }
  }


  /**
   * Get all the User of our database
   * 
   * @return array Will return an array with the whole data of our table database
   */
  public function getAll()
  {
    try {
      $user = $this->db->getAll($this->table, $this->columns);
      return $user;
    } catch(\Exception $exception) {
      throw $exception;
    }
  }

  /**
   * Will receive the data that we'll create into the User table
   * 
   * @param array $data {
   *    @type associative array
   *    Key => value
   * }
   * 
   * @return int $id Return the last id of the User created
   *  
   */
  public function createOne($data)
  {
    try {

      $columns = [];
      $values = [];

      foreach ($data as $key => $value) {
        
        array_push($columns, $key);
        if($key === "password") {
          $hashed_pass = password_hash($value, PASSWORD_DEFAULT);
          array_push($values, $hashed_pass);
        } else {
          array_push($values, $value);
        }
      }

      $id = $this->db->createOne($this->table, $columns, $values);
      return $id;
    } catch (\Exception $exception) {
      throw $exception;
    }
  }


  /**
   * Will delete the User defined with the Id that was passed by the parameters
   * 
   * @param int $id The id of the album to delete
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