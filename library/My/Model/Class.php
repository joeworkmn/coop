<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Class
 *
 * @author joseph
 */
class My_Model_Class extends Zend_Db_Table_Abstract
{
   protected $_name = "coop_classes";




   public function getAll()
   {
      return $this->fetchAll()->toArray();
   }

   // Returns the name of the class specified by the passed in id
   public function getName($id)
   {
      $sel = $this->select()->setIntegrityCheck(false);

      $res = $sel->from($this, array('name'))
                  ->where("id = $id");

      $row = $this->fetchRow($res)->toArray();

      return $row['name'];
   }

   // Returns full record for one class
   public function getClass($id)
   {

      $sel = $this->select()->setIntegrityCheck(false);

      $res = $sel->from($this)
                  ->where("id = $id");

      $row = $this->fetchRow($res)->toArray();

      return $row;

   }

   // Edits a classes information, i.e name, coordinator
   public function edit($id, $data)
   {
      $vals['name'] = $data['name'];
      $vals['coordinator'] = $data['coordinator'];

      $this->update($vals, "id = $id");

   }


   /*
    * Gets all the students enrolled in a specified class for the current semester
    * 
    * @param $id - the class id
    * 
    */

   public function getRollForCurrentSem($id)
   {
      $sel = $this->select()->setIntegrityCheck(false);

      $res = $sel->from('coop_users_semesters_view')
          ->where("classes_id = $id")
          ->where("current = 1");

      $recs = $this->fetchAll($res)->toArray();

      if (!is_array($recs)) {
         $recs = array();
      }

      return $recs;
   }

}
?>
