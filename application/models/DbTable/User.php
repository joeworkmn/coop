<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'coop_users';
    
    public function getAll()
    {
       return $this->fetchAll()->toArray();
    }
    

    public function getRow(array $where)
    {
       $keys = array_keys($where);
       $col = $keys[0];
       $val = $where[$col];
       $query = $this->select()->where("$col = ?", $val);
       $row = $this->fetchRow($query);
       $row = $row->toArray();
       return $row;
    }
 
    public function getRowById($id)
    {
       $row = $this->fetchRow("id = $id");
       $row = $row->toArray();
       return $row;
    }
 
    public function getRows(array $where)
    {
       $keys = array_keys($where);
       $col = $keys[0];
      // die($col);
       $val = $where[$col];
       $result = $this->select()->where("$col = ?", $val);
       $rows = $this->fetchAll($result);
       $rows = $rows->toArray();
       return $rows;
    }
 
    public function getId(array $where)
    {
       $keys = array_keys($where);
       $col = $keys[0];
      // die($col);
       $val = $where[$col];
       $query = $this->select()->from($this, array('id'))->where("$col = ?", $val);
       $row = $this->fetchRow($query);
       $row = $row->toArray();
       return $row['id'];
    }
 
    public function getCol($col, array $where)
    {
       $query = $this->select()->from($this, array($col));
       foreach ($where as $key => $val) {
 
          $query = $query->where("$key = ?", $val);
 
       }
       $row = $this->fetchRow($query);
       $row = $row->toArray();
       return $row["$col"];
 
    }
 
    public function getCols($col, array $where=array())
    {
       $query = $this->select()->from($this, $col);
       foreach ($where as $key => $val) {
 
          $query = $query->where("$key = ?", $val);
 
       }
       $result = $this->fetchAll($query);
       $rows = $result->toArray();
 
       foreach ($rows as $r) {
          $vals[] = $r[$col];
       }
       die(var_dump($vals));
 
       return $vals;
    }
 
    public function rowExists(array $where)
    {
       $keys = array_keys($where);
       $whereCol = $keys[0];
       $whereVal = $where[$whereCol];
 
       $query = $this->select()->where("$whereCol = ?", $whereVal);
       $row = $this->fetchRow($query);
 
       if (empty($row)) {
          return false;
       }
 
       return true;
    }

}

