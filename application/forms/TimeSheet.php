<?php

class Application_Form_TimeSheet extends Application_Form_CommonForm
{

    public function init()
    {
       $Assign = new My_Model_Assignment();
       $this->assignId = $Assign->getTimeSheetId();
       
       $this->setDecorators(array(array('ViewScript', 
                                  array('viewScript' => '/assignment/forms/timesheet.phtml'))));
       
       $this->makeStatics();

       
       $elems = new My_FormElement();
       $saveSubmit = $elems->getSubmit('saveOnly');
       $saveSubmit->setLabel('Save Only')
                  ->setAttrib('class', 'resubmit');
       $finalSubmit = $elems->getSubmit('finalSubmit');
       $finalSubmit->setLabel('Submit as Final')
                   ->setAttrib('class', 'resubmit');

       $this->addElements( array($saveSubmit, $finalSubmit));
       
       // Checks if there are submitted answers in order to populate the form with them.
       if ($this->populateForm === true) {
          $this->checkSubmittedAnswers(); 
          $this->populateJobsiteFields();
       }
       
       $this->setElementDecorators(array('ViewHelper',
                                        'Errors'
                                  ));
    }


    public function makeStatics()
    {
       $staticSubform = new Zend_Form_SubForm('static_tasks');
       $staticSubform->setElementsBelongTo('static_tasks');
       
       $jobSiteFields = $this->makeJobsiteFields();
       foreach ($jobSiteFields as $f) {
          $f->setAttrib('class', 'static');
       }

       $staticSubform->addElements($jobSiteFields);

       $elems = new My_FormElement();

       $formFields = array();
       $elem = $elems->getCommonTbox('fallHrs1', ' ');
       $formFields[] = $elem;

       $elem = $elems->getCommonTbox('fallHrs2', ' ');
       $formFields[] = $elem;

       $elem = $elems->getCommonTbox('fallHrs3', ' ');
       $formFields[] = $elem;

       $elem = $elems->getCommonTbox('fallHrs4', ' ');
       $formFields[] = $elem;
       
       $elem = $elems->getCommonTbox('fallHrs5', ' ');
       $formFields[] = $elem;
       
       $elem = $elems->getCommonTbox('fallTotalHrs', ' ');
       $formFields[] = $elem;
       
       $elem = $elems->getCommonTbox('springHrs1', ' ');
       $formFields[] = $elem;
       
       $elem = $elems->getCommonTbox('springHrs2', ' ');
       $formFields[] = $elem;
       
       $elem = $elems->getCommonTbox('springHrs3', ' ');
       $formFields[] = $elem;
       
       $elem = $elems->getCommonTbox('springHrs4', ' ');
       $formFields[] = $elem;
       
       $elem = $elems->getCommonTbox('springHrs5', ' ');
       $formFields[] = $elem;
       
       $elem = $elems->getCommonTbox('springTotalHrs', ' ');
       $formFields[] = $elem;
       
       $elem = $elems->getCommonTbox('summerHrs1', ' ');
       $formFields[] = $elem;
       
       $elem = $elems->getCommonTbox('summerHrs2', ' ');
       $formFields[] = $elem;
       
       $elem = $elems->getCommonTbox('summerTotalHrs', ' ');
       $formFields[] = $elem;

       foreach ($formFields as $f) {
          $f->setAttrib('size', '5');
          $f->setAttrib('class', 'static');
       }

       $staticSubform->addElements($formFields);
       
       $staticSubform->setElementDecorators(array('ViewHelper',
                                                  'Errors',
                                                  'Label'
                                           ));

       $this->addSubForm($staticSubform, 'static_tasks');

    }

}
