<?php

namespace App\Database;

use Helpers\ErrorReport;
use \mysqli;
use \Exception;

class MySql {
  protected $db_name;
  protected $password;
  protected $user;
  protected $host;

  public function __construct()
  {
    $this->db_name = $_ENV["DB_NAME"];
    $this->user = $_ENV["USER"];
    $this->password = $_ENV["PASSWORD"];
    $this->host = $_ENV["HOST"];

    $this->connect();
  }

  protected function createColumnsStatements($columns) {
    $columns_statment = "";
    $length = count($columns) - 1;
    for ($i=0; $i < count($columns); $i++) { 
      $columns_statment .= "$columns[$i]";
      if($i != $length) {
        $columns_statment .= ",";
      }
    }    
    return $columns_statment;
  }

  protected function createValuesStatements($columns) {
    $columns_statment = "";
    $length = count($columns) - 1;
    for ($i=0; $i < count($columns); $i++) { 
      $columns_statment .= "'$columns[$i]'";
      if($i != $length) {
        $columns_statment .= ",";
      }
    }    
    return $columns_statment;
  }

  protected function createConditionStatement($conditions) {
    $conditions_statement = "";
    $length = count($conditions);
    foreach ($conditions as $key => $value) {
      $conditions_statement .= "$key='$value'";
      if($conditions[$length - 1] != $value) {
        $conditions_statement .= ",";
      }
    }
    return $conditions_statement;
  }

  protected function conditionEmail($conditions) {
    $conditions_statement = "";
    foreach ($conditions as $key => $value) {
      $conditions_statement .= "$key='$value'";
    }
    return $conditions_statement;
  }

  public function connect(){
    try {
      if(!$this->connected) {
        $this->database = new mysqli($this->host, $this->user, $this->passwrod, $this->db_name);
        $this->connected = true;
      }

      return true;

    } catch(\Exception $exception) {
      
      $error = new ErrorReport("Something went wrong in the connection -> " . $exception->getMessage());
      return $error->database();
    }
  }

  public function disconnect()
  {
    $this->connected ? $this->database->close() : true;
  }

  public function getOne($table, $columns, $id)
  {
    if(!$this->connected) {
      $this->connect();
    }

    $columns_statment = $this->createColumnsStatements($columns);
    
    $result = $this->database->query("SELECT $columns_statment FROM $table WHERE id=$id");

    if($this->database->error) {
      throw new Exception("Something went wrong! -> " . $this->database->error);
    }

    $data = [];
    if($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
      }
    }
    return $data;
  }

  public function getAll($table, $columns, $condition = null){
    if(!$this->connected)
    {
      $this->connect();
    }

    $columns_statment = $this->createColumnsStatements($columns);

    $statement = "SELECT $columns_statment FROM $table";
    if($condition) {
      $conditions = $this->createConditionStatement($condition);
      $statement .= " WHERE $conditions";
    }

    $result = $this->database->query($statement);

    if($this->database->error) {
      throw new \Exception("Something went wrong in the query -> " . $this->database->error);
    }

    $data = [];
    if($result->num_rows > 0)
    {
      while($row = $result->fetch_assoc())
      {
        array_push($data, $row);
      }
    }

    return $data;
  }

  public function getLogin($table, $columns, $condition = NULL) {
    if(!$this->connected)
    {
      $this->connect();
    }

    $columns_statment = $this->createColumnsStatements($columns);

    $statement = "SELECT $columns_statment FROM $table";
    if($condition) {
      $conditions = $this->conditionEmail($condition);
      $statement .= " WHERE $conditions";
    }

    $result = $this->database->query($statement);

    if($this->database->error) {
      throw new \Exception("Something went wrong in the query -> " . $this->database->error);
    }

    $data = [];
    if($result->num_rows > 0)
    {
      while($row = $result->fetch_assoc())
      {
        array_push($data, $row);
      }
    }

    return $data;
  }

  public function createOne($table, $columns, $values) {
    if(!$this->connected) {
      $this->connect();
    }

    $columns_statment = $this->createColumnsStatements($columns);
    $values_statement = $this->createValuesStatements($values);
    $result = $this->database->query("INSERT INTO $table ($columns_statment) VALUES ($values_statement)");
    if($result  === TRUE) {
      $last_id = $this->database->insert_id;
      return $last_id; 
    } else if($this->database->error) {
      throw new \Exception("We can't create the user -> " . $this->database->error);
    }
     
  }

  public function deleteOne($table, $id)
  {
    if(!$this->connected) {
      $this->connect();
    }

    $result = $this->database->query("DELETE FROM $table WHERE id='$id'");

    if($this->database->error) {
      throw new \Exception("We can't delete the user -> " . $this->database->error);
    }

    return $result;
  }
}