<?php

class Application_Form_HistorySearch extends Zend_Form
{

    public function init()
    {
       //$this->setAction('/user/history-show');

       //$search = new Zend_Form_Element_Text('username');

       //$search->setLabel("Enter student's username:")
       //        ->setRequired(true)
       //        ->addFilter('StripTags')
       //        ->addFilter('StringTrim');

       //$submit = new Zend_Form_Element_Submit('submit');
       ////$submit = new Zend_Form_Element_Submit('submit');

       $elems = new My_FormElement();

       $search = $elems->getCommonTbox('username', "Enter student's username:");
       $search->setDecorators(array('ViewHelper',
                                    'Label'
                                    ));
       //$search->addValidator(new Zend_Validate_Db_RecordExists( array('table'=>'coop_users', 'field'=>'username')));

       $submit = $elems->getSubmit('Search');
       $submit->setDecorators(array('ViewHelper'
                                    ));

       $this->addElements(array($search, $submit));
    }


}

