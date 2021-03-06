<?php

class FormController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function studentInfoShowAction()
    {
       $coopSess = new Zend_Session_Namespace('coop');
       $as = new My_Model_Assignment();

       if ($coopSess->role === 'user') {
          $assignId = $as->getStuInfoId();
          if ($as->isDue($assignId)) {
             $this->view->message = "<p class=error> This assignment is past it's due date </p>";
             return;
          }
          
          $classId = $coopSess->currentClassId;
          $username = $coopSess->username;
          $semId = $coopSess->currentSemId;
       } else {
          $subForStudentData = $coopSess->submitForStudentData;
          $classId = $subForStudentData['classes_id'];
          $username = $subForStudentData['username'];
          $semId = $subForStudentData['semesters_id'];
       }


       $form = new Application_Form_StudentInfo( array('classId' => $classId,
           'semId' => $semId,
           'username' => $username));

       $this->view->form = $form;

       if ($this->getRequest()->isPost()) {
          $data = $_POST;

          //die(var_dump($form->personalInfo->getValues()));

          if ($form->isValid($data)) {

             $Assign = new My_Model_Assignment();
             $result = $Assign->submitStuInfoSheet($form);
             if ($result === true) {
                $this->view->resultMessage = "<p class=success> Success </p>";
             }

          } else {
             $this->view->resultMessage = "<p class=error> Errors on Form </p>";
          }
       }
    }


    public function studentInfoEditAction()
    {
       $coopSess = new Zend_Session_Namespace('coop');
       $as = new My_Model_Assignment();

       if ($coopSess->role === 'user') {
          $assignId = $as->getStuInfoId();
          if ($as->isDue($assignId)) {
             $this->view->message = "<p class=error> This assignment is past it's due date </p>";
             return;
          }
          
          $classId = $coopSess->currentClassId;
          $username = $coopSess->username;
          $semId = $coopSess->currentSemId;
       } else {
          $subForStudentData = $coopSess->submitForStudentData;
          $classId = $subForStudentData['classes_id'];
          $username = $subForStudentData['username'];
          $semId = $subForStudentData['semesters_id'];
       }

       $form = new Application_Form_StudentInfo( array('classId' => $classId,
           'semId' => $semId,
           'username' => $username));

       $form->setSubmissions();

       $this->view->form = $form;

       if ($this->getRequest()->isPost()) {
          $data = $_POST;
          //die(var_dump($data['empInfo']));

          // Need to get the correct form that was submitted so it can be validated. 
          $submittedForm = $form->submissions[$data['empInfo']['empInfoId']];

          if ($submittedForm->isValid($data)) {
             //die(var_dump($form->empInfo->getElement('empInfoId')->getValue()));
             //die(var_dump($form->empInfo->empInfoId->getValue()));

             $submittedForm->setSubmissionTypeToUpdate();
             $Assign = new My_Model_Assignment();
             $result = $Assign->submitStuInfoSheet($submittedForm);
             if ($result === true) {
                $this->view->resultMessage = "<p class=success> Success </p>";
             }

             $form->setSubmissions();
             //die(var_dump(count($form->submissions)));

          } else {
             $this->view->resultMessage = "<p class=error> Errors on Form </p>";
          }

          //die(var_dump($form->getErrors()));
       }
       

    }


    public function coopAgreementAction()
    {

       $coopSess = new Zend_Session_Namespace('coop');

       if ($coopSess->role === 'user') {
           $as = new My_Model_Assignment();
           $assignId = $as->getCoopAgreementId();
           if ($as->isDue($assignId)) {
              $this->view->message = "<p class=error> This assignment is past it's due date </p>";
              return;
           }
           
           $classId = $coopSess->currentClassId;
           $username = $coopSess->username;
           $semId = $coopSess->currentSemId;
       } else {
           $subForStudentData = $coopSess->submitForStudentData;
           $classId = $subForStudentData['classes_id'];
           $username = $subForStudentData['username'];
           $semId = $subForStudentData['semesters_id'];
       }
       
       $form = new Application_Form_Agreement(array('username' => $username, 
                                                    'classId' => $classId,
                                                    'semId' => $semId,
                                                    'populateForm' => false ));
       $form->populateJobsiteFields();

       $this->view->form = $form;

       if ($this->getRequest()->isPost()) {
          $data = $_POST;
          //die(var_dump($data));

          if ($form->isValid($data)) {
             //die(var_dump($form));
             $as = new My_Model_Assignment();
             //$Jobsite = new My_Model_Jobsites();
             //$res = $as->submitStudentEval($form);
             $res = $as->submitAgreementForm($form);

             if ($res === true) {
                $this->view->resultMessage = "<p class='success'> Success </p>";
             } else if ($res === 'submitted') {
                $this->view->resultMessage = "<p class='error'> Assignment has already been submitted </p>";
             } else {
                $this->view->resultMessage = "<p class='error'> Error </p>";
             }
          } else {
             $this->view->resultMessage = "<p class=error> Errors on Form </p>";
          }
       }

    }

    public function coopAgreementPdfAction()
    {
       if ($this->getRequest()->isPost()) {
          $data = $_POST;

          $data = rawurlencode(serialize($data));

          $server = $_SERVER['SERVER_NAME'];

          $coopSess = new Zend_Session_Namespace('coop');
          $baseUrl = $coopSess->baseUrl;

          // Returns the rendered HTML as a string
          //$page = file_get_contents("http://$server$baseUrl/form/coop-agreement-pdf?data=".$data);

          exec(APPLICATION_PATH . "/../pdfs/wkhtmltopdf-i386  
              http://$server$baseUrl/form/coop-agreement-pdf?role=A592NXZ71680STWVR926\&data=$data " . 
                  APPLICATION_PATH . '/../pdfs/coopAgreement.pdf');

          $pdfPath = APPLICATION_PATH . '/../pdfs/coopAgreement.pdf';
          $pdf = Zend_Pdf::load($pdfPath);
          header("Content-Disposition: attachment; filename=Coop Agreement.pdf");
          header("Content-type: application/x-pdf");
          $pdfData = $pdf->render();

          echo $pdfData;

          $this->_helper->layout->disableLayout();
          $this->_helper->viewRenderer->setNoRender(true);


       // For the wkhtmltopdf GET request
       } else if ($this->getRequest()->isGet()) {

          if (isset($_GET['data'])) {
             $data = $_GET['data'];

             $data = unserialize(rawurldecode($data));

             //die($data);

             $form = new Application_Form_Contract();
             $form->removeElement('Submit');
             $form->populate($data);

             $this->view->form = $form;

             $this->_helper->layout->disableLayout();
          }


       }

    }

    public function editDisclaimerAction()
    {

       if ($this->getRequest()->isPost()) {
          $data = $_POST;
          unset($data['Submit']);
          //die(var_dump($data));
          $db = new My_Db();
          try {
             $db->update('coop_disclaimer_text', $data);
             $this->view->message = "<p class=success> Updated successfully </p>";
          } catch(Exception $e) {
             $this->view->message = "<p class=error> Error occured </p>";
          }
       }

       $form = new Zend_Form();

       $elems = new My_FormElement();

       $db = new My_Db();
       $res = $db->select()->from('coop_disclaimer_text');
       $res = $db->fetchAll($res);
       $res = $res[0];

       $tArea = $elems->getCommonTarea('text', 'Enter text for disclaimer page:');
       $tArea->removeFilter('StripTags');
       $submit = $elems->getSubmit();
       $form->addElements(array($tArea, $submit));
       $form->populate($res);

       $this->view->form = $form;
    }


    private function handlePost($form, $data)
    {
       $coopSess = new Zend_Session_Namespace('coop');
       if ($form->isValid($data)) {
          if ($data['agreement'] == 'agree') {
             $coopSess->validData = $data;
             return true;
          } else {
             $this->view->message = 'Must agree before continuing';
             $form->populate($data);
             return false;
          }
       } else {
          return false;
       }
       
    }

    //public function stuinfoFormTemplateAction()
    //{
    //   //$this->view->form = new Application_Form_StudentInfo();
    //}

}







