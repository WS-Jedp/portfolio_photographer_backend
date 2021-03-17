<?php

namespace App\Database;

use \mysqli;
use \Exception;

class MySql {
  protected $db_name;
  protected $password;
  protected $user;
  protected $host;
  protected $connected = false;

  public function __construct()
  {
    $this->db_name = $_ENV["DB_NAME"];
    $this->user = $_ENV["USER"];
    $this->password = $_ENV["PASSWORD"];
    $this->host = $_ENV["HOST"];

    $this->connect();
  }

  protected function createColumnsStatements($columns, $table = null) {
    $columns_statment = "";
    $length = count($columns) - 1;
    for ($i=0; $i < count($columns); $i++) { 
      if($table) {
        $columns_statment .= "$table.$columns[$i]";
      } else {
        $columns_statment .= "$columns[$i]";
      }
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

  protected function createConditionStatement($conditions, $table = null) {
    $conditions_statement = "";
    $lastKey = array_key_last($conditions);
    
    foreach ($conditions as $key => $value) {
      if($table) {
        $conditions_statement .= "$table.$key='$value'";
      } else {
        $conditions_statement .= "$key='$value'";
      }
      if($conditions[$lastKey] != $value) {
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
        $this->database = new mysqli($this->host, $this->user, $this->password, $this->db_name);
        $this->connected = true;
      }
    } catch(\Exception $exception) {
      throw new Exception($exception->getMessage());
    }
  }

  public function disconnect()
  {
    $this->connected ? $this->database->close() : true;
  }


  /**
   * FUNCTION GET ONE
   * function getOne() -> Will return one element of the indicated tables with the defined columns according to the id given
   * 
   * @param string $table Must be the name of the table
   * @param array columns = Must be the columns that we want to return
   * @param integer id = Must be the one element that we want to find
   */
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


  /**
   * Will return all the elemnts with the columns that we indicate of the table that we indicate.
   * If we want, we can pass a third optional parameter to create a condition to our SQL query
   * 
   * @param string $table Must be the name of the column
   * @param string[] $columns Must bet the columns' name that we want to get
   * @param string $condition Optional! - If we want a condition into our SQL statement
   * @return array 
   */
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


  //LOGIN QUERY
  /**
   * @param string $table The name of the table where the User is saved
   * @param string[] $columns The columns that we want to receive from our database
   * @param string[] $condition [Optional!] The condition of the statemet
   * 
   * @return array Will return an Associative Arrary with the information of the User that we indicate 
   */
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


  // CREATE QUERY
  /**
   * Will create one elment into the table that we indicate.
   * 
   * @param string $table The name of the table where we can to create a new element
   * @param string[] $columns The names of the columns that we want to create
   * @param string[] $valus The values of the columns that we want to create
   * 
   * @return int last_id
   */
  public function createOne($table, $columns, $values) {
    if(!$this->connected) {
      $this->connect();
    }

    $columns_statment = $this->createColumnsStatements($columns);
    $values_statement = $this->createValuesStatements($values);

    $result = $this->database->query("INSERT INTO $table ($columns_statment) VALUES ($values_statement)");

    if($this->database->error) {
      throw new \Exception("We can't create the user -> " . $this->database->error);
    }

    if($result == true) {
      $last_id = $this->database->insert_id;
      return $last_id; 
    } 
     
  }


  // DELETE QUERY
  /**
   * Will delete one element of the table
   * 
   * @param string $table The name of the table
   * @param int $id The id of the element that we want to delete from the indicated table
   * 
   * @return bool $result
   */
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


  // INNER QUERIES
  /**
   * Create a INNER JOIN query
   * 
   * @param string $table The main table to make the query
   * @param string $innerTable The table which we'll make the INNER JOIN
   * @param string[] $columns The columns of the main and inner table.
   * @param string[] $on The keys that relate each table with the another 
   * @param string $condition [Optional!] If exist a condition will be added to the SQL Statement
   */
  public function innerJoin($table, $innerTable, $columns, $on, $condition = null)
  {
    $columnsMainTable = $this->createColumnsStatements($columns["main"], $table);
    $columnsInnerTable = $this->createColumnsStatements($columns["inner"], $innerTable);

    $statement = "SELECT {$columnsMainTable}, {$columnsInnerTable} FROM $table INNER JOIN $innerTable ON $table.{$on["main"]} = $innerTable.{$on["inner"]}";

    if($condition) {
      $conditionStatement = $this->createConditionStatement($condition, $table);
      $statement .= " WHERE $conditionStatement";
    }

    $result = $this->database->query($statement);

    if($this->database->error) {
        throw new Exception("Something went wrong ->" . $this->database->error);
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

  /**
   * Create a multiple INNER JOIN query for the Many To Many tables
   * 
   * @param string $table The main table to make the query
   * @param array[string[]] $innerTable Array of string with the tables name which we'll make the INNER JOIN
   * @param string[] $columns The columns of the main and inner table.
   * @param string[] $on The keys that relate each table with the another 
   * @param string $condition [Optional!] If exist a condition will be added to the SQL Statement
   */
  public function multipleInnerJoin($table, $innerTables, $columns, $on, $condition = null)
  {

    $columnsStatement = '';
    $last_key = array_key_last($columns);
    foreach($columns as $key => $value) {
      $columnsStatement .= $this->createColumnsStatements($value, $key);
      if($columns[$last_key] != $value) {
        $columnsStatement .= ',';
      }
    }

    $innerJoinStatements = '';
    $last_key = array_key_last($innerTables);
    foreach ($innerTables as $innerTable => $innerTableKey) {
      $innerJoinStatements .= "INNER JOIN $innerTable ON $table.$innerTableKey = $innerTable.{$on[$innerTable]} ";
    }

    $statement = "SELECT $columnsStatement FROM $table $innerJoinStatements";

    if($condition) {
      $conditionStatement = $this->createConditionStatement($condition, $table);
      $statement .= " WHERE $conditionStatement";
    }

    $result = $this->database->query($statement);

    if($this->database->error) {
        throw new Exception("Something went wrong ->" . $this->database->error);
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

  
}

 