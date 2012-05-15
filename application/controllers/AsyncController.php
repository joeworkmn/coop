<?php

class AsyncController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function studentRecSearchResultAction()
    {
       if ($this->_request->isPost()) {
          $data = $_POST;
          //die(var_dump($data));

          $user = new My_Model_User();
          $results = $user->searchStudentRecs($data);

          

          $this->view->results = $results;

       } else {
          $this->_helper->viewRenderer->setNoRender();
       }

       $this->_helper->getHelper('layout')->disableLayout();
    }

    public function submissionRecsAction()
    {
       if ($this->getRequest()->isPost()) {
          $data = array();
          if (isset($_POST['data'][0])) {
             $data = $_POST['data'][0];
          }
          //die(var_dump($data));

          //die(var_dump($arr));

          $sub = new My_Model_SubmittedAssignment();
          $recs = $sub->getSubmissionRec($data);

          $this->view->recs = $recs;

          //die(var_dump($recs));

       } else {
          $this->_helper->viewRenderer->setNoRender();
       }

       $this->_helper->getHelper('layout')->disableLayout();
    }

    public function classRollJsonAction()
    {
       if ($this->getRequest()->isPost()) {

          $id = $_POST['id'];

          $class = new My_Model_Class();

          $rows = $class->getRollForCurrentSem($id);

          if (!is_array($rows)) {
             $rows = array();
          }

          //die(var_dump($rows));

          $json = Zend_Json_Encoder::encode($rows);
          //$json = json_encode($rows);

          echo $json;

       } 

       $this->_helper->viewRenderer->setNoRender();
       $this->_helper->getHelper('layout')->disableLayout();
    }

    public function viewStuInfoSheetAction()
    {
       $user = new My_Model_User();

       $form = new Application_Form_StudentInfo();

       $as = new My_Model_Assignment();
       $form = $as->populateStuInfoSheet($form, array('username' => 'johndoe'));

       $empinfo = $user->getEmpInfo(array('username' => 'johndoe', 'classes_id' => 1, 'semesters_id' => 9));

       $this->view->empinfo = $empinfo;
       $this->view->form = $form;
    }


}





