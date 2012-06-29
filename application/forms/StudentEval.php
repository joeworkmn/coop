<?php

class Application_Form_StudentEval extends Zend_Form
{
    protected $classId;
    protected $assignId;

    public function init()
    {
       $aq = new My_Model_AssignmentQuestions();
       $as = new My_Model_Assignment();

       if (isset($this->assignId)) {
          $asId = $this->assignId;
       } else {
          $asId = $as->getStudentEvalId();
       }

       $questions = $aq->getChildParentQuestions(array('classId' => $this->classId, 'assignId' => $asId));

       //die(var_dump($questions));


      $this->setDecorators(array(array('ViewScript', 
                                   array('viewScript' => '/assignment/student-eval-template.phtml'))));

       foreach ($questions as $q) {
          if ($q['question_type'] !== 'parent') {
             $elem = new Zend_Form_Element_Radio($q['id']);
             $elem->setLabel($q['question_text'])
                  ->setRequired(true)
                  ->setSeparator('')
                  ->setMultiOptions(array('1' => '1',
                                          '2' => '2',
                                          '3' => '3',
                                          '4' => '4',
                                          '5' => '5'));
                  //->setMultiOptions(array('Strongly Disagree' => 'Strongly Disagree',
                  //                        'Disagree' => 'Disagree',
                  //                        'Neutral' => 'Neutral',
                  //                        'Agree' => 'Agree',
                  //                        'Strongly Agree' => 'Strongly Agree'));
          } else {
             $elem = new Zend_Form_Element_Hidden($q['id']);
             $elem->setLabel($q['question_text']);
          }

          $this->addElements(array($elem));
       }

       $elems = new My_FormElement();
       $submit = $elems->getSubmit();

       $this->addElements(array($submit));

       $this->setElementDecorators(array('ViewHelper',
                                        'Errors'
                                  ));
    }

    public function setClassId($classId)
    {
       //die($classId);
       $this->classId = $classId;
    }

    public function setAssignId($assignId)
    {
       $this->assignId = $assignId;

    }


}

