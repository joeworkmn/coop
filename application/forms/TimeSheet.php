<?php

class Application_Form_TimeSheet extends Application_Form_CommonForm
{

    public function init()
    {
       $Assign = new My_Model_Assignment();
       $this->assignId = $Assign->getTimeSheetId();

       //die(var_dump($this->username));
       $User = new My_Model_User();
       $user = $User->fetchRow("username = '" . $this->username . "'");

       $studentsName = new Zend_Form_Element_Text('studentsName');
       $studentsName->addFilter("StripTags")
                    ->setValue($user->fname . ' ' . $user->lname);
       $this->addElement($studentsName);
       
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
       $pdfSubmit = $elems->getSubmit('pdfSubmit');
       $pdfSubmit->setLabel('PDF');

       $this->addElements( array($saveSubmit, $finalSubmit, $pdfSubmit));
       
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

       $hoursFields = array();
       $elem = $elems->getCommonTbox('fallHrs1', ' ');
       $elem->setAttrib('semester', 'fall');
       $hoursFields[] = $elem;

       $elem = $elems->getCommonTbox('fallHrs2', ' ');
       $elem->setAttrib('semester', 'fall');
       $hoursFields[] = $elem;

       $elem = $elems->getCommonTbox('fallHrs3', ' ');
       $elem->setAttrib('semester', 'fall');
       $hoursFields[] = $elem;

       $elem = $elems->getCommonTbox('fallHrs4', ' ');
       $elem->setAttrib('semester', 'fall');
       $hoursFields[] = $elem;
       
       $elem = $elems->getCommonTbox('fallHrs5', ' ');
       $elem->setAttrib('semester', 'fall');
       $hoursFields[] = $elem;
       
       $elem = $elems->getCommonTbox('fallTotalHrs', ' ');
       $hoursFields[] = $elem;

       $startEndDates = array();
       $startEndDates[] = $elems->getCommonTbox('fall_start_date', ' ');
       $startEndDates[] = $elems->getCommonTbox('fall_end_date', ' ');

       
       $elem = $elems->getCommonTbox('springHrs1', ' ');
       $elem->setAttrib('semester', 'spring');
       $hoursFields[] = $elem;
       
       $elem = $elems->getCommonTbox('springHrs2', ' ');
       $elem->setAttrib('semester', 'spring');
       $hoursFields[] = $elem;
       
       $elem = $elems->getCommonTbox('springHrs3', ' ');
       $elem->setAttrib('semester', 'spring');
       $hoursFields[] = $elem;
       
       $elem = $elems->getCommonTbox('springHrs4', ' ');
       $elem->setAttrib('semester', 'spring');
       $hoursFields[] = $elem;
       
       $elem = $elems->getCommonTbox('springHrs5', ' ');
       $elem->setAttrib('semester', 'spring');
       $hoursFields[] = $elem;
       
       $elem = $elems->getCommonTbox('springTotalHrs', ' ');
       $hoursFields[] = $elem;

       $startEndDates[] = $elems->getCommonTbox('spring_start_date', ' ');
       $startEndDates[] = $elems->getCommonTbox('spring_end_date', ' ');
       
       $elem = $elems->getCommonTbox('summerHrs1', ' ');
       $elem->setAttrib('semester', 'summer');
       $hoursFields[] = $elem;
       
       $elem = $elems->getCommonTbox('summerHrs2', ' ');
       $elem->setAttrib('semester', 'summer');
       $hoursFields[] = $elem;
       
       $elem = $elems->getCommonTbox('summerTotalHrs', ' ');
       $hoursFields[] = $elem;
       
       $startEndDates[] = $elems->getCommonTbox('summer_start_date', ' ');
       $startEndDates[] = $elems->getCommonTbox('summer_june_end_date', ' ');
       $startEndDates[] = $elems->getCommonTbox('summer_july_start_date', ' ');
       $startEndDates[] = $elems->getCommonTbox('summer_end_date', ' ');

       foreach ($hoursFields as $f) {
          $f->setAttrib('size', '8');
          $f->setAttrib('class', 'static');
          $f->setRequired(false);
       }

       foreach ($startEndDates as $se) {
          $se->setDecorators(array('ViewHelper',
                                   'Errors'))
             ->setAttrib('class', 'static textinput-line')
             ->setAttrib('size', '5')
             ->setRequired(false);
       }

       $staticSubform->addElements($hoursFields);
       $staticSubform->addElements($startEndDates);
       
       //$staticSubform->setElementDecorators(array('ViewHelper',
       //                                           'Errors',
       //                                           'Label'
       //                                    ));

       $this->addSubForm($staticSubform, 'static_tasks');

    }

}

