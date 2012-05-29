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


    // displays a table of a student's assignment status (whether or not assignments have
    // been submitted)
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

          $user = new My_Model_User();

          $recText = $user->getSemesterInfo($data);
          $recText = $recText[0];
          $this->view->recText = $recText;

          $this->view->recs = $recs;

          //die(var_dump($recs));

       } else {
          $this->_helper->viewRenderer->setNoRender();
       }

       $this->_helper->getHelper('layout')->disableLayout();
    }

    
    public function viewStuInfoSheetAction()
    {
       if ($this->getRequest()->isPost()) {
          $data = array();
          if (isset($_POST['data'][0])) {
             $data = $_POST['data'][0];
          }

          

          $user = new My_Model_User();

          $recText = $user->getSemesterInfo($data);

          //die(var_dump($recText));
          if (!empty($recText)) {
             $recText = $recText[0];
          }

          $this->view->recText = $recText;

          $form = new Application_Form_StudentInfo();

          $as = new My_Model_Assignment();
          $form = $as->populateStuInfoSheet($form, array('username' => $data['username']));

          $empinfo = $user->getEmpInfo($data);

          $this->view->empinfo = $empinfo;
          $this->view->form = $form;

       } else {
          $this->_helper->viewRenderer->setNoRender();
       }

       $this->_helper->getHelper('layout')->disableLayout();
    }


    // Gets all students for a specific class (used to populate student dropdown based
    // on selected class in the assignment submit view)
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

    public function midtermReportAction()
    {
       $this->_helper->getHelper('layout')->disableLayout();

       if ($this->getRequest()->isPost()) {
          $form = new Application_Form_MidtermReport();

          $data = array();
          if (isset($_POST['data'][0])) {
             $data = $_POST['data'][0];
          }

          //die(var_dump($data));

          // To get text for the record being viewed (student's name, semester, class)
          $user = new My_Model_User();
          $recText = $user->getSemesterInfo($data);
          if (!empty($recText)) {
             $recText = $recText[0];
          }
          $this->view->recText = $recText;

          $as = new My_Model_Assignment();
          // Midterm Report's id
          $data['assignments_id'] = $as->getMidtermId();

          // check if midterm report has been submitted first
          $res = $as->isSubmitted($data);
          // if not submitted
          if ($res === false) {
             $this->view->submitted = false;
             return;
          }

          // Populate the form based on data
          $form = $as->populateMidTermReport($form, $data);

          // Take out submit button since it's for coordinator view
          $form->removeElement("Submit");
          $this->view->form = $form;
          
       } else {
          // If not a POST request, don't render the view
          $this->_helper->viewRenderer->setNoRender();
       } 
    }
}





